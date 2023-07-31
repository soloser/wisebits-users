<?php
declare(strict_types = 1);

namespace WiseUsers\Validators\Rules;

use Respect\Validation\Rules\AbstractRule;
use WiseUsers\Repositories\BlockListRepositoryInterface;

final class BlockedMailer extends AbstractRule
{

    /**
     * @param BlockListRepositoryInterface $repository
     */
    public function __construct(private readonly BlockListRepositoryInterface $repository)
    {}

    /**
     * @param $input
     * @return bool
     */
    public function validate($input): bool
    {
        $parts = explode('@', $input);
        if (count($parts) !== 2) {
            $valid = true;
        } else {
            $valid = !$this->repository->isBlocked(end($parts));
        }
        return $valid;
    }
}