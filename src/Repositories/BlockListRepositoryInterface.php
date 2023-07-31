<?php
declare(strict_types = 1);

namespace WiseUsers\Repositories;

interface BlockListRepositoryInterface
{

    /**
     * @param string $value
     * @return bool
     */
    public function isBlocked(string $value): bool;
}