<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING_CONFIRMATION = 'pending_confirmation';
    public const STATUS_NEEDS_PROCESSING = 'perlu_diproses';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PACKED = 'packed';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const CANCELLATION_PENDING = 'pending';
    public const CANCELLATION_APPROVED = 'approved';
    public const CANCELLATION_REJECTED = 'rejected';

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'expires_at',
        'confirmed_at',
        'cancelled_at',
        'notes',
        'buyer_confirmed_at',
        'cancellation_reason',
        'cancellation_requested_at',
        'cancellation_status',
        'cancellation_reviewed_at',
        'cancellation_reviewed_by',
        'cancellation_admin_note',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'expires_at' => 'datetime',
            'buyer_confirmed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'cancellation_requested_at' => 'datetime',
            'cancellation_reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function cancellationReviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'cancellation_reviewed_by'
        );
    }

    /*public function hasPendingCancellation(): bool
    {
        return $this->cancellation_status
            === self::CANCELLATION_PENDING;
    }

    public function canRequestCancellation(): bool
    {
        return $this->status === self::STATUS_NEEDS_PROCESSING
            && $this->cancellation_status === null;
    }

    public function canAdminConfirm(): bool
    {
        return $this->status === self::STATUS_NEEDS_PROCESSING
            && ! $this->hasPendingCancellation();
    }*/
}
