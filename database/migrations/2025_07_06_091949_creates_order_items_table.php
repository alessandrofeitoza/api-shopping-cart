<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->uuid('order_id');
            $table->uuid('shopping_cart_id');

            $table->primary(['order_id', 'shopping_cart_id']);

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('shopping_cart_id')->references('id')->on('shopping_carts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
