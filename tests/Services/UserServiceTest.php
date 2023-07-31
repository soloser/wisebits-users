<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Services;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WiseUsers\Entities\User;
use WiseUsers\Loggers\LoggerInterface;
use WiseUsers\Repositories\UserRepositoryInterface;
use WiseUsers\Services\UserService;
use WiseUsers\Validators\UserValidator;

class UserServiceTest extends TestCase
{

    #[DataProvider('deleteLoggingDataProvider')]
    public function testDeleteLogging(bool $success, bool $logged): void
    {
        $user = $this->createUser();
        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->method('softDelete')
            ->willReturn($success);
        $validator = $this->createStub(UserValidator::class);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly($logged ? 1 : 0))
            ->method('log');
        $service = new UserService(
            $repository,
            $validator,
            $logger
        );

        $this->assertEquals($success, $service->delete($user));
    }

    public static function deleteLoggingDataProvider(): array
    {
        return [
            'successful' => [true, true],
            'failed' => [false, false],
        ];
    }

    #[DataProvider('saveLoggingDataProvider')]
    public function testSaving(bool $isUserNew, bool $success, bool $logged): void
    {
        $user = $this->createUser($isUserNew);
        $repository = $this->createMock(UserRepositoryInterface::class);
        if ($isUserNew) {
            $repository->expects($this->once())
                ->method('create')
                ->willReturn(1);
        } else {
            $repository->expects($this->once())
                ->method('update')
                ->willReturn($success);
        }
        $validator = $this->createStub(UserValidator::class);
        $validator->method('validate')->willReturn(true);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly($logged ? 1 : 0))
            ->method('log');
        $service = new UserService(
            $repository,
            $validator,
            $logger
        );

        $this->assertEquals($success, $service->save($user));
    }

    public static function saveLoggingDataProvider(): array
    {
        return [
            'successful create' => [true, true, false],
            'successful update' => [false, true, true],
            'failed update' => [false, false, false],
        ];
    }

    public function testSuccessfulValidation(): void
    {
        $user = $this->createUser();
        $service = $this->createForValidation(true);
        $this->assertEquals(true, $service->save($user));
    }

    public function testFailedValidation(): void
    {
        $user = $this->createUser();
        $service = $this->createForValidation(false);
        $this->assertEquals(false, $service->save($user));
    }

    protected function createForValidation(bool $valid): UserService
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('create')->willReturn(1);
        $repository->method('update')->willReturn(true);

        $validator = $this->createStub(UserValidator::class);
        $validator->method('validate')->willReturn($valid);
        $logger = $this->createMock(LoggerInterface::class);
        if (!$valid) {
            $logger->expects($this->never())->method('log');
            $repository->expects($this->never())->method('create');
            $repository->expects($this->never())->method('update');
        }

        return new UserService(
            $repository,
            $validator,
            $logger
        );
    }

    protected function createUser(bool $isNew = false): User
    {
        $user = $this->createStub(User::class);
        $user->method('getId')
            ->willReturn($isNew ? null : 23);
        return $user;
    }
}