<?php

declare(strict_types=1);

use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->float('original_price');
            $table->float('total_price');
            $table->enum(
                'status',
                array_column(
                    OrderStatusEnum::cases(),
                    'value'
                )
            )->default(
                OrderStatusEnum::PENDING->value
            );
            $table->enum(
                'payment_method',
                array_column(
                    PaymentMethodEnum::cases(),
                    'value'
                )
            );
            $table->float('discount');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
