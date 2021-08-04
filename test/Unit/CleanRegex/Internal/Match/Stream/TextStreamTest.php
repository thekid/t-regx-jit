<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Stream\StreamBase;
use TRegx\CleanRegex\Internal\Match\Stream\TextStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\TextStream
 */
class TextStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $stream = new TextStream($this->mock('all', $this->matchesOffset()));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['Lorem', 'Foo', 'Bar'], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $stream = new TextStream($this->mock('first', new RawMatchOffset([['Lorem ipsum', 1]], 0)));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('Lorem ipsum', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new TextStream($this->mock('firstKey', 123));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    private function mock(string $methodName, $value): StreamBase
    {
        /** @var StreamBase|MockObject $stream */
        $stream = $this->createMock(StreamBase::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function matchesOffset(): RawMatchesOffset
    {
        return new RawMatchesOffset([[
            ['Lorem', 1],
            ['Foo', 2],
            ['Bar', 3],
        ]]);
    }
}
