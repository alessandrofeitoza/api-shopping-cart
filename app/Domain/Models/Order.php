<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'total_price',
        'status',
        'payment_method',
        'discount',
        'created_at',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'total_price' => 'float',
        'status' => 'string',
        'payment_method' => 'string',
        'discount' => 'float',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            ShoppingCart::class,
            'order_items',
            'order_id',
            'shopping_cart_id'
        );
    }

    public function setUserId(UuidInterface $userId): void
    {
        $this->attributes['user_id'] = $userId->toString();
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->attributes['total_price'] = $totalPrice;
    }

    public function setStatus(OrderStatusEnum $status): void
    {
        $this->attributes['status'] = $status->value;
    }

    public function setOriginalPrice(float $price): void
    {
        $this->attributes['original_price'] = $price;
    }

    public function setPaymentMethod(PaymentMethodEnum $paymentMethod): void
    {
        $this->attributes['payment_method'] = $paymentMethod->value;
    }

    public function setDiscount(float $discount): void
    {
        $this->attributes['discount'] = $discount;
    }

    public function setId(UuidInterface $uuid): void
    {
        $this->id = $uuid->toString();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }
}
