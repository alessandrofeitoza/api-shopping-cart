<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description = null): void
    {
        $this->description = $description;
    }
}
