<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Optional;

trait AssertsOptionalPresent
{
    public function assertOptionalIsPresent(Optional $optional): void
    {
        $expected = $optional->get();
        Assert::assertSame($expected, $optional->orElse(Functions::assertArgumentless()));
        Assert::assertSame($expected, $optional->orReturn('Foo'));
        Assert::assertSame($expected, $optional->orThrow(new \Exception()));
    }

    public function assertOptionalPresent(Optional $optional, $expected): void
    {
        Assert::assertSame($expected, $optional->orElse(Functions::assertArgumentless()));
        Assert::assertSame($expected, $optional->orReturn('Foo'));
        Assert::assertSame($expected, $optional->orThrow(new \Exception()));
        Assert::assertSame($expected, $optional->get());
    }
}