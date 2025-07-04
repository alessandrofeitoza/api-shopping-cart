<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Infrastructure\Supports\Enums\UserRoleEnum;
use App\Infrastructure\Supports\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'status',
        'roles',
        'last_login',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'roles' => 'json',
    ];

    public function __construct(array $attributes = [])
    {
        $this->addRole(UserRoleEnum::ROLE_USER);
        $this->setStatus(UserStatusEnum::AWAITING_CONFIRMATION);

        parent::__construct($attributes);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setId(UuidInterface $uuid): void
    {
        $this->id = $uuid->toString();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function setName(string $name): void
    {
        $this->attributes['name'] = $name;
    }

    public function setEmail(string $email): void
    {
        $this->attributes['email'] = $email;
    }

    public function setStatus(UserStatusEnum $status): void
    {
        $this->attributes['status'] = $status->value;
    }

    public function addRole(UserRoleEnum $role): void
    {
        $roles = $this->roles ?? [];

        if (false === in_array($role->value, $roles, strict: true)) {
            $roles[] = $role->value;
            $this->roles = $roles;
        }
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
