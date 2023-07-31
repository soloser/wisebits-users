<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Validators\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WiseUsers\Repositories\BlockListRepositoryInterface;
use WiseUsers\Validators\Rules\BlockedMailer;

class BlockedMailerTest extends TestCase
{

    public function testDomainPassing(): void
    {
        $email = 'oleg@gmail.com';
        $domain = 'gmail.com';
        $repository = $this->createMock(BlockListRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('isBlocked')
            ->with($this->equalTo($domain))
            ->willReturn(false);
        $rule = new BlockedMailer($repository);
        $this->assertEquals(true, $rule->validate($email));
    }

    #[DataProvider('emailDataProvider')]
    public function tesValidate(string $email, bool $valid): void
    {
        $repository = $this->createStub(BlockListRepositoryInterface::class);
        $repository->method('isBlocked')
            ->will($this->returnCallback(function (string $value) {
                return $value === 'mail.ru';
            }));
        $rule = new BlockedMailer($repository);
        $this->assertEquals($valid, $rule->validate($email));
    }

    public static function emailDataProvider(): array
    {
        return [
            ['ivan@gmail.com', true],
            ['ivan@mail.ru', false],
            ['mail.ru', true],
            ['', true],
        ];
    }
}