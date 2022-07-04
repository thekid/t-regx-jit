<?php
namespace Test\Feature\CleanRegex\_figures\template\mask;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->mask('*', ['*' => 'Bar']);
        // when, then
        $this->assertTrue($pattern->test('Foo:Bar'), 'Failed to assert that placeholder was optional and present');
    }

    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholderAbsent()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->mask('*', ['*' => 'Bar']);
        // when, then
        $this->assertTrue($pattern->test('Foo:'), "Failed to assert that placeholder was optional and absent");
    }

    /**
     * @test
     */
    public function shouldNotMatchPartialOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->mask('*', ['*' => 'Bar']);
        // when, then
        $this->assertTrue($pattern->fails('Foo:Ba'), "Failed to assert that partial of placeholder was matched");
    }

    /**
     * @test
     */
    public function shouldNotApplyQuantifierBefore()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->mask('*', ['*' => 'Bar']);
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }

    /**
     * @test
     */
    public function shouldNotApplyQuantifierBeforeEmpty()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->mask('*', ['*' => '']);
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousFigures()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Supplied a superfluous figure. Used 0 placeholders, but 1 figures supplied.");
        // when
        Pattern::template('Foo')->mask('*', ['*' => 'Bar']);
    }

    /**
     * @test
     */
    public function shouldThrowForUnderflowFigures()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 2 placeholders, but 1 figures supplied.');
        // when
        Pattern::template('@@')->mask('*', ['*' => 'Bar']);
    }
}
