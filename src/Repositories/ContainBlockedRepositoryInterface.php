<?php
declare(strict_types = 1);

namespace WiseUsers\Repositories;

interface ContainBlockedRepositoryInterface
{

    /**
     * @param string $value
     * @return bool
     */
    public function containBlocked(string $value): bool;
}