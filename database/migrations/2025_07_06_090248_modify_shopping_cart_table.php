<?php

declare(strict_types=1);

use App\Infrastructure\Supports\Enums\ShoppingCartStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shopping_carts', function (Blueprint $table): void {
            $table->enum(
                'status',
                array_column(
                    ShoppingCartStatusEnum::cases(),
                    'value'
                )
            )->default(
                ShoppingCartStatusEnum::AWAITING->value
            );
        });
    }

    public function down(): void
    {
        Schema::table('shopping_carts', function (Blueprint $table): void {
            $table->dropColumn(['status']);
        });
    }
};
