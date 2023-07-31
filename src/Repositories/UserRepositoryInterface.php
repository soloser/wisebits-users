<?php
declare(strict_types = 1);

namespace WiseUsers\Repositories;

use WiseUsers\Entities\User;

interface UserRepositoryInterface
{

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User;

    /**
     * @return User[]
     */
    public function getAll(): array;

    /**
     * @param User $user
     * @return int
     */
    public function create(User $user): int;

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function softDelete(User $user): bool;
}