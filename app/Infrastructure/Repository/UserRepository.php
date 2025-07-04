<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Models\User;
use App\Domain\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findAll(): Collection
    {
        return User::all();
    }

    public function find(string $id): User
    {
        return User::findOrFail($id);
    }

    public function save(User $user): User
    {
        $user->save();

        return $user;
    }

    public function remove(User $user): void
    {
        $user->delete();
    }
}
