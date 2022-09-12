<?php
namespace Test\Feature\CleanRegex\PatternList\chainedReplace\with;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\PatternList::chainedReplace
 * @covers \TRegx\CleanRegex\Composite\ChainedReplace
 */
class PatternListTest extends TestCase
{
    /**
     * @test
     * @dataProvider times
     * @param int $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $pattern = Pattern::list(\array_slice([
            "at's ai",
            "th__r you're bre",
            'nk __ath',
            'thi__ing',
            '(\s+|\?)',
        ], 0, $times));
        // when
        $replaced = $pattern->chainedReplace("Do you think that's air you're breathing now?")->with('__');
        // then
        $this->assertSame($expected, $replaced);
    }

    public function times(): array
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think th__r you're breathing now?"],
            [2, 'Do you think __athing now?'],
            [3, 'Do you thi__ing now?'],
            [4, 'Do you __ now?'],
            [5, 'Do__you______now__'],
            [6, 'Do__you______now__'],
            [7, 'Do__you______now__'],
        ];
    }

    /**
     * @test
     */
    public function shouldReplacePcreReference()
    {
        // given
        $pattern = Pattern::list(['One(1)', 'Two(2)', 'Three(3)']);
        // when
        $replaced = $pattern->chainedReplace('One1, Two2, Three3')->with('$1');
        // then
        $this->assertSame('$1, $1, $1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithPcreReferences_whole()
    {
        // given
        $pattern = Pattern::list(['One(1)', 'Two(2)', 'Three(3)']);
        // when
        $replaced = $pattern->chainedReplace('One1, Two2, Three3')->with('<$0>');
        // then
        $this->assertSame('<$0>, <$0>, <$0>', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithPcreReferences_whole_curlyBrace()
    {
        // given
        $pattern = Pattern::list(['One(1)', 'Two(2)', 'Three(3)']);
        // when
        $replaced = $pattern->chainedReplace('One1, Two2, Three3')->with('<${0}>');
        // then
        $this->assertSame('<${0}>, <${0}>, <${0}>', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithPcreReferences_whole_backslash()
    {
        // given
        $pattern = Pattern::list(['One(1)', 'Two(2)', 'Three(3)']);
        // when
        $replaced = $pattern->chainedReplace('One1, Two2, Three3')->with('<\0>');
        // then
        $this->assertSame('<\0>, <\0>, <\0>', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithPcreReferences_twoDigits_backslash()
    {
        // given
        $tenGroups = \str_repeat('()', 10);
        $pattern = Pattern::list(["One$tenGroups(1)", "Two$tenGroups(2)"]);
        // when
        $replaced = $pattern->chainedReplace('One1, Two2')->with('<${11}>');
        // then
        $this->assertSame('<${11}>, <${11}>', $replaced);
    }
}
