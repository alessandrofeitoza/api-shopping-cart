<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use App\Domain\Models\ProductCategory;
use PHPUnit\Framework\TestCase;

class ProductCategoryTest extends TestCase
{
    public function testBasicPropertiesFromCategoryModel(): void
    {
        $category = new ProductCategory();
        $category->setName('Bebidas');
        $category->setDescription('Bebidas em geral');

        $this->assertEquals('Bebidas', $category->name);
        $this->assertEquals('Bebidas em geral', $category->description);
    }
}
