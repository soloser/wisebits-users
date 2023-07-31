<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Validators\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WiseUsers\Entities\User;
use WiseUsers\Repositories\GetByRepositoryInterface;
use WiseUsers\Validators\Rules\UniqueField;

class UniqueFieldTest extends TestCase
{
    #[DataProvider('validateDataProvider')]
    public function testValidate(int $existedId, ?int $exceptId, string $input, bool $valid): void
    {
        $repository = $this->createStub(GetByRepositoryInterface::class);
        $repository->method('getBy')
            ->will($this->returnCallback(function (array $criteria, array $except) use ($existedId) {
                if (isset($except['id']) && $except['id'] === $existedId) {
                    return [];
                } else {
                    return [$this->createStub(User::class)];
                }
            }));
        $rule = new UniqueField($repository, 'name', $exceptId);
        $this->assertEquals($valid, $rule->validate($input));
    }

    public static function validateDataProvider(): array
    {
        return [
            [1, 2, 'some string', false],
            [1, 1, 'somestring', true],
            [1, null, ' string', false],
        ];
    }
}