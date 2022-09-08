<?php
namespace Test\Feature\CleanRegex\Replace\limit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_withString()
    {
        // when
        $replaced = pattern('er|ab|ay|ey')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->limit(2)
            ->with('*');
        // then
        $this->assertSame('P. Sh*man, 42 Wall*y way, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_all()
    {
        // given
        $pattern = pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)');
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        $pattern
            ->replace($subject)
            ->limit(2)
            ->callback(function (Detail $detail) {
                // then
                $this->assertSame(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

                return '';
            });
    }

    /**
     * @test
     */
    public function shouldThrowOnNegativeLimit()
    {
        // given
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative limit: -1");
        // when
        pattern('')->replace('')->limit(-1);
    }

    /**
     * @test
     * @dataProvider limitAndExpectedResults
     * @param int $limit
     * @param string $expectedResult
     */
    public function shouldReplaceNOccurrences(int $limit, string $expectedResult)
    {
        // when
        $replaced = pattern('[0-3]')->replace('0 1 2 3')->limit($limit)->with('*');
        // then
        $this->assertSame($expectedResult, $replaced);
    }

    function limitAndExpectedResults(): array
    {
        return [
            [0, '0 1 2 3'],
            [1, '* 1 2 3'],
            [2, '* * 2 3'],
            [3, '* * * 3'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_only_onNegativeLimit()
    {
        // given
        $replace = Pattern::of('Foo')->replace('Bar');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $replace->limit(-2);
    }
}