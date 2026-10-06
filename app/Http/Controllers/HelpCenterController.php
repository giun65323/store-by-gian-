<?php

namespace App\Http\Controllers;

use App\Services\GianCareAiService;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    protected GianCareAiService $aiService;

    public function __construct(GianCareAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Tampilkan Halaman Pusat Bantuan (Help Center).
     */
    public function index(Request $request)
    {
        $role = $request->query('tab', 'buyer'); // 'buyer' or 'seller'
        return view('help-center.index', compact('role'));
    }

    /**
     * Endpoint API untuk Chat Assistant AI Gian Care.
     */
    public function chatAi(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array'],
        ]);

        $message = $request->input('message');
        $history = $request->input('history', []);

        $reply = $this->aiService->generateReply($message, $history);

        return response()->json([
            'status' => 'success',
            'reply'  => $reply,
        ]);
    }
}
