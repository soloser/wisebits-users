<?php
declare(strict_types = 1);

namespace WiseUsers\Validators;

use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Email;
use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\Regex;
use Respect\Validation\Rules\StringType;
use WiseUsers\Entities\User;
use Respect\Validation\Validator as v;
use WiseUsers\Repositories\BlockListRepositoryInterface;
use WiseUsers\Repositories\ContainBlockedRepositoryInterface;
use WiseUsers\Repositories\GetByRepositoryInterface;
use WiseUsers\Validators\Rules\BlockedMailer;
use WiseUsers\Validators\Rules\ContainBlocked;
use WiseUsers\Validators\Rules\UniqueField;

class UserValidator
{

    /**
     * @param BlockListRepositoryInterface $emailRepository
     * @param ContainBlockedRepositoryInterface $nameRepository
     * @param GetByRepositoryInterface $findByRepository
     */
    public function __construct(
        private readonly BlockListRepositoryInterface $emailRepository,
        private readonly ContainBlockedRepositoryInterface $nameRepository,
        private readonly GetByRepositoryInterface $findByRepository
    ) {}

    /**
     * @param User $user
     * @return bool
     */
    public function validate(User $user): bool
    {
        $nameRules = new AllOf(
            new StringType(),
            new Regex('/^[a-z0-9]+$/'),
            new Length(8, 64),
            new ContainBlocked($this->nameRepository),
            new UniqueField($this->findByRepository, 'name', $user->getId())
        );
        $emailRules = new AllOf(
            new Email(),
            new Length(null, 255),
            new BlockedMailer($this->emailRepository),
            new UniqueField($this->findByRepository, 'email', $user->getId())
        );
        $validator = v::attribute('name', $nameRules)->attribute('email', $emailRules);
        return $validator->validate($user);
    }
}