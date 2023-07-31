<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Validators\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WiseUsers\Repositories\ContainBlockedRepositoryInterface;
use WiseUsers\Validators\Rules\ContainBlocked;

class ContainBlockedTest extends TestCase
{

    public function testPassing(): void
    {
        $string = 'some string';
        $repository = $this->createMock(ContainBlockedRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('containBlocked')
            ->with($this->equalTo($string))
            ->willReturn(false);
        $rule = new ContainBlocked($repository);
        $this->assertEquals(true, $rule->validate($string));
    }

    #[DataProvider('stringsDataProvider')]
    public function testValidate(string $string, bool $valid): void
    {
        $repository = $this->createStub(ContainBlockedRepositoryInterface::class);
        $repository->method('containBlocked')
            ->will($this->returnCallback(function (string $value) {
                return str_contains($value, '<BLOCKED>');
            }));
        $rule = new ContainBlocked($repository);
        $this->assertEquals($valid, $rule->validate($string));
    }

    public static function stringsDataProvider(): array
    {
        return [
            ['', true],
            ['some <BLOCKED>string', false],
            ['<BLOCKED><BLOCKED>', false],
        ];
    }
}