<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    private const VALUES = [
        [
            'name' => 'Informática',
            'description' => null,
        ],
        [
            'name' => 'Livros',
            'description' => null,
        ],
        [
            'name' => 'Bebidas',
            'description' => 'Refrigerantes, Bebidas Alcoolicas, Sucos',
        ],
    ];

    public function run(): void
    {
        foreach (self::VALUES as $value) {
            $object = new ProductCategory();
            $object->setName($value['name']);
            $object->setDescription($value['description']);

            $object->saveOrFail();
        }
    }
}
