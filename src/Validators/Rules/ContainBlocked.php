<?php
declare(strict_types = 1);

namespace WiseUsers\Validators\Rules;

use Respect\Validation\Rules\AbstractRule;
use WiseUsers\Repositories\ContainBlockedRepositoryInterface;

final class ContainBlocked extends AbstractRule
{

    /**
     * @param ContainBlockedRepositoryInterface $repository
     */
    public function __construct(private readonly ContainBlockedRepositoryInterface $repository)
    {}

    /**
     * @param $input
     * @return bool
     */
    public function validate($input): bool
    {
        return !$this->repository->containBlocked($input);
    }
}