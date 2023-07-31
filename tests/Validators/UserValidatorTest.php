<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Validators;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WiseUsers\Entities\User;
use WiseUsers\Repositories\BlockListRepositoryInterface;
use WiseUsers\Repositories\ContainBlockedRepositoryInterface;
use WiseUsers\Repositories\GetByRepositoryInterface;
use WiseUsers\Validators\UserValidator;

class UserValidatorTest extends TestCase
{

    #[DataProvider('simpleValidationDataProvider')]
    public function testSimpleValidation(User $user, bool $isValid): void
    {
        $validator = $this->createValidator();
        $this->assertEquals($isValid, $validator->validate($user));
    }

    #[DataProvider('customValidationDataProvider')]
    public function testCustomValidation(bool $emailBlocked, bool $nameBlocked, bool $existed, bool $isValid): void
    {
        $user = $this->getValidUser();
        $validator = $this->createValidator($emailBlocked, $nameBlocked, $existed);
        $this->assertEquals($isValid, $validator->validate($user));
    }

    public static function simpleValidationDataProvider(): array
    {
        return [
            [static::getValidUser(), true],
            [new User(1, 'ivan petrov', 'ivan@gmail.com'), false],
            [new User(1, 'ivanpetrov', 'ivangmail.com'), false],
            [new User(1, 'ivan', 'ivan@gmail.com'), false],
            [new User(1, 'IVANPetrov', 'ivan@gmail.com'), false],
            [new User(1, 'IVANPetrov', 'ivan2gmail.com'), false],
        ];
    }

    public static function customValidationDataProvider(): array
    {
        return [
            'valid' => [false, false, false, true],
            'blocked email' => [true, false, false, false],
            'blocked name' => [false, true, false, false],
            'not unique' => [false, false, true, false],
        ];
    }

    /**
     * @param bool $emailBlocked
     * @param bool $nameBlocked
     * @param bool $existed
     * @return UserValidator
     */
    private function createValidator(bool $emailBlocked = false, bool $nameBlocked = false, bool $existed = false): UserValidator
    {
        $emailRepository = $this->createStub(BlockListRepositoryInterface::class);
        $emailRepository->method('isBlocked')
            ->willReturn($emailBlocked);
        $nameRepository = $this->createStub(ContainBlockedRepositoryInterface::class);
        $nameRepository->method('containBlocked')
            ->willReturn($nameBlocked);
        $getByRepository = $this->createStub(GetByRepositoryInterface::class);
        $getByRepository->method('getBy')
            ->willReturn($existed ? [$this->createStub(User::class)] : []);

        return new UserValidator(
            $emailRepository,
            $nameRepository,
            $getByRepository
        );
    }

    public static function getValidUser(): User
    {
        return new User(1, 'ivanpetrov', 'ivan@gmail.com');
    }
}