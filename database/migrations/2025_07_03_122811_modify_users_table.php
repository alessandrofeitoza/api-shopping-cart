<?php

declare(strict_types=1);

use App\Infrastructure\Supports\Enums\UserStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->json('roles');
            $table->enum(
                'status',
                array_column(
                    UserStatusEnum::cases(),
                    'value'
                )
            )->default(
                UserStatusEnum::AWAITING_CONFIRMATION->value
            );
            $table->dateTime('last_login')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['roles', 'status', 'last_login']);
        });
    }
};
