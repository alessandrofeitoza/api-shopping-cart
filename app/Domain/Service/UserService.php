<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Models\User;
use App\Domain\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {
    }

    public function findAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function find(UuidInterface $id): User
    {
        return $this->repository->find($id->toString());
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->fill($data);

        return $this->repository->save($user);
    }

    public function update(UuidInterface $id, array $data): User
    {
        $user = $this->find($id);
        $user->setName($data['name'] ?? $user->name);
        $user->setPassword($data['password'] ?? $user->password);
        $user->setEmail($data['email'] ?? $user->email);

        return $this->repository->save($user);
    }

    public function remove(UuidInterface $id): void
    {
        $user = $this->repository->find($id->toString());

        $this->repository->remove($user);
    }
}
