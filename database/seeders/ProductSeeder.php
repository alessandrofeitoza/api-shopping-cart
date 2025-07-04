<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('product_categories')->pluck('id', 'name');

        $values = [
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000001',
                'name' => 'Notebook Dell',
                'description' => 'Notebook com 16GB RAM e SSD',
                'category' => 'Informática',
                'price' => 4500.00,
                'photo' => null,
                'quantity' => 10,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000002',
                'name' => 'Mouse Gamer',
                'description' => 'Mouse RGB 6400 DPI',
                'category' => 'Informática',
                'price' => 150.00,
                'photo' => null,
                'quantity' => 50,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000003',
                'name' => 'Livro PHP Moderno',
                'description' => 'Aprenda PHP atualizado',
                'category' => 'Livros',
                'price' => 89.90,
                'photo' => null,
                'quantity' => 25,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000004',
                'name' => 'Cerveja Artesanal IPA',
                'description' => 'Garrafa de 600ml',
                'category' => 'Bebidas',
                'price' => 15.90,
                'photo' => null,
                'quantity' => 100,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000005',
                'name' => 'Água Mineral 500ml',
                'description' => null,
                'category' => 'Bebidas',
                'price' => 3.50,
                'photo' => null,
                'quantity' => 300,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000006',
                'name' => 'Monitor 24\"',
                'description' => 'Full HD, HDMI e VGA',
                'category' => 'Informática',
                'price' => 750.00,
                'photo' => null,
                'quantity' => 15,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000007',
                'name' => 'Livro Design Patterns',
                'description' => 'Padrões de Projeto em Software',
                'category' => 'Livros',
                'price' => 120.00,
                'photo' => null,
                'quantity' => 12,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000008',
                'name' => 'Teclado Mecânico',
                'description' => 'Switch Blue, RGB',
                'category' => 'Informática',
                'price' => 320.00,
                'photo' => null,
                'quantity' => 20,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000009',
                'name' => 'Suco Natural 1L',
                'description' => 'Suco de laranja integral',
                'category' => 'Bebidas',
                'price' => 9.90,
                'photo' => null,
                'quantity' => 80,
            ],
            [
                'id' => '11111111-aaaa-bbbb-cccc-000000000010',
                'name' => 'Livro Clean Code',
                'description' => 'O clássico sobre código limpo',
                'category' => 'Livros',
                'price' => 150.00,
                'photo' => null,
                'quantity' => 30,
            ],
        ];

        foreach ($values as $value) {
            $product = new Product();
            $product->setId(Uuid::fromString($value['id']));
            $product->setName($value['name']);
            $product->setDescription($value['description']);
            $product->setCategoryId($categories[$value['category']] ?? null);
            $product->setPrice($value['price']);
            $product->setPhoto($value['photo']);
            $product->setQuantity($value['quantity']);
            $product->saveOrFail();
        }
    }
}
