<?php
declare(strict_types = 1);

namespace WiseUsers\Services;

use WiseUsers\Entities\User;
use WiseUsers\Loggers\LoggerInterface;
use WiseUsers\Repositories\UserRepositoryInterface;
use WiseUsers\Validators\UserValidator;

class UserService
{

    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserValidator $validator
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserValidator $validator,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        if (!$this->validator->validate($user)) {
            $saved = false;
        } elseif ($user->getId() !== null) {
            $saved = $this->userRepository->update($user);
            if ($saved) {
                $this->logger->log($user, 'User updated');
            }
        } else {
            $this->userRepository->create($user);
            $saved = true;
        }

        return $saved;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        $deleted = $this->userRepository->softDelete($user);

        if ($deleted) {
            $this->logger->log($user, 'User deleted');
        }

        return $deleted;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        return $this->userRepository->getById($id);
    }
}