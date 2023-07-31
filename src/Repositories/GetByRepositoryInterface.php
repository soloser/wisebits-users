<?php
declare(strict_types = 1);

namespace WiseUsers\Repositories;

use WiseUsers\Entities\User;

interface GetByRepositoryInterface
{

    /**
     * @param array $criteria
     * @param array $except
     * @return User[]
     */
    public function getBy(array $criteria, array $except = []): array;
}