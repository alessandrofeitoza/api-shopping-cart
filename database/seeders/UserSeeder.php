<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\User;
use App\Infrastructure\Supports\Enums\UserStatusEnum;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    private const VALUES = [
        [
            'id' => '1789995d-dceb-4df1-a1e0-853d88edab74',
            'name' => 'Alessandro Feitoza',
            'email' => 'alessandro@email.com',
            'status' => UserStatusEnum::ACTIVE,
        ],
        [
            'id' => '101c750a-24d9-4b94-aa74-01386852da04',
            'name' => 'Sarah da Silva',
            'email' => 'sarah@email.com',
            'status' => UserStatusEnum::AWAITING_CONFIRMATION,
        ],
        [
            'id' => 'ad099cfa-7ac9-4193-ac84-8e5cab1e969b',
            'name' => 'Chiquim das Tapiocas',
            'email' => 'chiquim@email.com',
            'status' => UserStatusEnum::BLOCKED,
        ],
    ];

    public function run(): void
    {
        foreach (self::VALUES as $value) {
            $object = new User();
            $object->setId(Uuid::fromString($value['id']));
            $object->setName($value['name']);
            $object->setEmail($value['email']);
            $object->setStatus($value['status']);
            $object->setPassword('123456');

            $object->saveOrFail();
        }
    }
}
