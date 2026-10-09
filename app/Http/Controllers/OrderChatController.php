<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderChatController extends Controller
{
    /**
     * Ambil seluruh riwayat pesan chat pesanan ini (AJAX / JSON).
     */
    public function fetchMessages(string $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $user = Auth::user();

        // Validasi akses
        if (!$user->isAdmin() && $user->id !== $order->buyer_id && $user->id !== $order->seller_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tandai pesan masuk sebagai telah dibaca
        OrderMessage::where('order_id', $order->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $order->messages()
            ->with(['sender:id,name,role_id,avatar'])
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id'           => $msg->id,
                    'sender_id'    => $msg->sender_id,
                    'sender_name'  => $msg->sender?->name ?? 'User',
                    'sender_avatar'=> $msg->sender?->avatar_url ?? '',
                    'is_me'        => $msg->sender_id === $user->id,
                    'message'      => $msg->message,
                    'is_read'      => $msg->is_read,
                    'created_time' => $msg->created_at->format('H:i'),
                    'created_date' => $msg->created_at->translatedFormat('d M Y, H:i'),
                ];
            });

        return response()->json([
            'status'       => 'success',
            'order_number' => $order->order_number,
            'messages'     => $messages,
        ]);
    }

    /**
     * Kirim pesan baru ke Penjual / Pembeli (AJAX / JSON).
     */
    public function sendMessage(Request $request, string $order_number)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $order = Order::where('order_number', $order_number)->firstOrFail();
        $user = Auth::user();

        // Validasi akses
        if (!$user->isAdmin() && $user->id !== $order->buyer_id && $user->id !== $order->seller_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tentukan penerima pesan (jika pengirim buyer -> penerima seller, begitu sebaliknya)
        $receiverId = ($user->id === $order->buyer_id) ? $order->seller_id : $order->buyer_id;

        $msg = OrderMessage::create([
            'order_id'    => $order->id,
            'sender_id'   => $user->id,
            'receiver_id' => $receiverId,
            'message'     => $request->input('message'),
            'is_read'     => false,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => [
                'id'           => $msg->id,
                'sender_id'    => $msg->sender_id,
                'sender_name'  => $user->name,
                'sender_avatar'=> $user->avatar_url ?? '',
                'is_me'        => true,
                'message'      => $msg->message,
                'created_time' => $msg->created_at->format('H:i'),
                'created_date' => $msg->created_at->translatedFormat('d M Y, H:i'),
            ],
        ]);
    }

    /**
     * Ambil notifikasi pesan belum dibaca & daftar chat terbaru untuk user yang sedang login.
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['unread_count' => 0, 'threads' => []]);
        }

        // Total pesan masuk yang belum dibaca untuk user ini
        $unreadCount = OrderMessage::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Ambil daftar percakapan terbaru (dikelompokkan per order)
        $recentMessages = OrderMessage::where(function ($q) use ($user) {
                $q->where('receiver_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            })
            ->with(['order.product', 'order.store', 'order.buyer', 'order.seller', 'sender'])
            ->latest()
            ->get()
            ->unique('order_id')
            ->take(8)
            ->values()
            ->map(function ($msg) use ($user) {
                $order = $msg->order;
                if (!$order) return null;

                $isSeller = ($user->id === $order->seller_id);
                $otherPartyName = $isSeller 
                    ? ($order->buyer?->name ?? 'Pembeli') 
                    : ($order->store?->name ?? ($order->seller?->name ?? 'Penjual'));

                $unreadInOrder = OrderMessage::where('order_id', $order->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'id'               => $msg->id,
                    'order_number'     => $order->order_number,
                    'product_name'     => $order->product_name,
                    'other_party_name' => $otherPartyName,
                    'sender_name'      => $msg->sender?->name ?? 'Pengguna',
                    'sender_avatar'    => $msg->sender?->avatar_url ?? '',
                    'message'          => $msg->message,
                    'is_me'            => $msg->sender_id === $user->id,
                    'unread_count'     => $unreadInOrder,
                    'created_time'     => $msg->created_at->diffForHumans(),
                    'chat_url'         => route('orders.show', $order->order_number) . '?open_chat=1',
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'status'       => 'success',
            'unread_count' => $unreadCount,
            'threads'      => $recentMessages,
        ]);
    }
}

