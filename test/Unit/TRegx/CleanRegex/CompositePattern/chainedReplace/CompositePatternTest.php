<?php
namespace Test\Unit\TRegx\CleanRegex\CompositePattern\chainedReplace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use function array_slice;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider times
     * @param int    $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $patterns = [
            "at's ai",
            "th__r you're bre",
            "nk __ath",
            "thi__ing",
            '(\s+|\?)',
        ];
        $pattern = CompositePattern::of(array_slice($patterns, 0, $times));

        // when
        $replaced = $pattern->chainedReplace("Do you think that's air you're breathing now?", '__');

        // then
        $this->assertEquals($expected, $replaced);
    }

    function times()
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think th__r you're breathing now?"],
            [2, "Do you think __athing now?"],
            [3, "Do you thi__ing now?"],
            [4, "Do you __ now?"],
            [5, "Do__you______now__"],
            [6, "Do__you______now__"],
            [7, "Do__you______now__"],
        ];
    }
}
