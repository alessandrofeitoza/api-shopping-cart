<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\UuidInterface;

class Product extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'category_id',
        'price',
        'photo',
        'quantity',
    ];

    protected $casts = [
        'id' => 'string',
        'price' => 'float',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function setId(UuidInterface $uuid): void
    {
        $this->id = $uuid->toString();
    }

    public function setName(string $name): void
    {
        $this->attributes['name'] = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->attributes['description'] = $description;
    }

    public function setCategoryId(?int $categoryId): void
    {
        $this->attributes['category_id'] = $categoryId;
    }

    public function setPrice(float $price): void
    {
        $this->attributes['price'] = $price;
    }

    public function setPhoto(?string $photo): void
    {
        $this->attributes['photo'] = $photo;
    }

    public function setQuantity(int $quantity): void
    {
        $this->attributes['quantity'] = $quantity;
    }
}
