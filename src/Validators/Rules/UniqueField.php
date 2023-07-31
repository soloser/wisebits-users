<?php
declare(strict_types = 1);

namespace WiseUsers\Validators\Rules;

use Respect\Validation\Rules\AbstractRule;
use WiseUsers\Repositories\GetByRepositoryInterface;

final class UniqueField extends AbstractRule
{

    /**
     * @param GetByRepositoryInterface $repository
     * @param string $field
     * @param int|null $exceptId
     */
    public function __construct(
        private readonly GetByRepositoryInterface $repository,
        private readonly string $field,
        private readonly ?int $exceptId = null
    ) {}

    /**
     * @param $input
     * @return bool
     */
    public function validate($input): bool
    {
        $except = $this->exceptId !== null ? ['id' => $this->exceptId] : [];
        $entities = $this->repository->getBy([$this->field => $input], $except);
        return empty($entities);
    }
}