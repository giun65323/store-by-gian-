<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'delivery_type',
    ];

    /**
     * Product belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product belongs to a Store.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Product has many reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Average rating of product.
     */
    public function averageRating(): float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round((float) $avg, 1) : 5.0;
    }

    /**
     * Total review count.
     */
    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Check if product is active and visible.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && is_null($this->deleted_at);
    }
}
