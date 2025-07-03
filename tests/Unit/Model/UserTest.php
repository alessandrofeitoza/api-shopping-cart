<?php

declare(strict_types=1);

namespace tests\Unit\Model;

use App\Domain\Models\User;
use App\Infrastructure\Supports\Enums\UserRoleEnum;
use App\Infrastructure\Supports\Enums\UserStatusEnum;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testBasicPropertiesFromUserModelWithDefaultStatusAndRole(): void
    {
        $user = new User();
        $user->setName('Chiquim da Silva');
        $user->setEmail('chiquim@email.com');
        $user->setPassword('123456');
        $user->setId(Uuid::uuid4());

        $this->assertTrue(password_verify('123456', $user->password));
        $this->assertEquals('Chiquim da Silva', $user->name);
        $this->assertEquals('chiquim@email.com', $user->email);
        $this->assertInstanceOf(UuidInterface::class, Uuid::fromString($user->id));
        $this->assertEquals(UserStatusEnum::AWAITING_CONFIRMATION->value, $user->status);
        $this->assertEquals(UserRoleEnum::ROLE_USER->value, $user->roles[0]);
    }

    public function testStatusAndRolePropertiesFromUserModel(): void
    {
        $user = new User();
        $user->setStatus(UserStatusEnum::ACTIVE);
        $user->addRole(UserRoleEnum::ROLE_ADMIN);

        $this->assertEquals(UserStatusEnum::ACTIVE->value, $user->status);
        $this->assertEquals(UserRoleEnum::ROLE_USER->value, $user->roles[0]);
        $this->assertEquals(UserRoleEnum::ROLE_ADMIN->value, $user->roles[1]);
    }
}
