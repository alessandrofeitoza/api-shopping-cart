<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findAll(): Collection;

    public function find(string $id): User;

    public function save(User $user): User;

    public function remove(User $user): void;
}
