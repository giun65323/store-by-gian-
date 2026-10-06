<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'avatar',
        'phone_number',
        'gender',
        'date_of_birth',
        'is_active',
        'ktp_photo',
        'selfie_photo',
        'seller_status',
        'seller_applied_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'seller_applied_at'  => 'datetime',
            'date_of_birth'      => 'date',
            'password'           => 'hashed',
            'is_active'          => 'boolean',
        ];
    }

    /**
     * Get avatar URL or empty string.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        return '';
    }

    /**
     * User belongs to a Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User (Seller) has one Store.
     */
    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'seller_id');
    }

    /**
     * User has many products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Support tickets opened by this user.
     */
    public function reportedTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    /**
     * Support tickets assigned to this admin.
     */
    public function handledTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'admin_id');
    }

    /**
     * Support messages sent by this user.
     */
    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'sender_id');
    }

    /**
     * Orders purchased by this user (as Buyer).
     */
    public function purchasedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id')->latest();
    }

    /**
     * Orders sold by this user (as Seller).
     */
    public function soldOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id')->latest();
    }

    /**
     * RBAC Helper: Check if user is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    /**
     * RBAC Helper: Check if user is Seller.
     */
    public function isSeller(): bool
    {
        return $this->role?->name === 'seller';
    }

    /**
     * RBAC Helper: Check if user is Buyer.
     */
    public function isBuyer(): bool
    {
        return $this->role?->name === 'buyer';
    }
}
