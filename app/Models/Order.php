<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'store_id',
        'product_id',
        'product_name',
        'product_image',
        'category_name',
        'price',
        'quantity',
        'total_price',
        'status',
        'voucher_code',
        'payment_method',
        'delivery_type',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    /**
     * Buyer who placed the order.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Seller of the ordered item.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Store of the seller.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Product purchased.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Chat messages between buyer and seller for this specific order.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class, 'order_id')->oldest();
    }

    /**
     * Generate unique random Itemku-style order number.
     * Format: OD000000 + 9 random digits, e.g. OD000000164288180
     */
    public static function generateOrderNumber(): string
    {
        do {
            $randomDigits = str_pad((string) random_int(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            $orderNumber = 'OD000000' . $randomDigits;
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Generate unique voucher / license code.
     * Format: e.g. CAFexmCsaBM
     */
    public static function generateVoucherCode(): string
    {
        return Str::random(11);
    }
}
