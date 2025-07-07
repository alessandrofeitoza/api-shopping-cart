<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Infrastructure\Supports\Enums\ShoppingCartStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ShoppingCart extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'quantity',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'user_id' => 'string',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = ShoppingCartStatusEnum::AWAITING;
        parent::__construct($attributes);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setId(UuidInterface $uuid): void
    {
        $this->id = $uuid->toString();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function setProductId(string $productId): void
    {
        $this->attributes['product_id'] = $productId;
    }

    public function setUserId(string $userId): void
    {
        $this->attributes['user_id'] = $userId;
    }

    public function setQuantity(int $quantity): void
    {
        $this->attributes['quantity'] = $quantity;
    }

    public function addQuantity(int $quantity): void
    {
        $this->attributes['quantity'] += $quantity;
    }
}
