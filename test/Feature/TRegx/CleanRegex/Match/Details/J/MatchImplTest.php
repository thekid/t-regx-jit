<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\J;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupGetRightValue()
    {
        // when
        pattern('(?<group>Foo)(?<group>Bar)', 'J')
            ->match('FooBar')
            ->first(function (Detail $match) {
                // given
                $group = $match->group('group');

                // when + then
                $this->assertEquals(['group', null], $match->groups()->names());
                $this->assertEquals(1, $group->index());
                $this->assertEquals(0, $group->offset());
                $this->assertEquals('Foo', $group->text());
                $this->assertEquals('Foo', $match->get('group'));
            });
    }

    /**
     * @test
     */
    public function shouldLastGroupNotBeMatched()
    {
        // when
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->first(function (Detail $match) {
                // given
                $group = $match->group('group');

                // when + then
                $this->assertEquals(['group', null, null], $match->groups()->names());
                $this->assertEquals(1, $group->index());
                $this->assertFalse($group->matched(), "Failed asserting that the last group was not matched");
            });
    }

    /**
     * @test
     */
    public function shouldGetThrow_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group', but the group was not matched");

        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->first(function (Detail $match) {
                // when
                $match->get('group');
            });
    }

    /**
     * @test
     */
    public function shouldStreamHandleDuplicateGroups_notMatched()
    {
        /**
         * This test verified whether GroupFacade calls Stream with
         * all().
         * - fluent() is used here to use streams.
         * - forEach() is used here to call all(), but any method that
         * works for multiple matches would do. Don't use first() or findFirst().
         */

        // when
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->fluent()
            ->forEach(function (NotMatchedGroup $group) {
                $this->assertFalse($group->matched());
            });
    }

    /**
     * @test
     */
    public function shouldStreamHandleDuplicateGroups_matched()
    {
        /**
         * This test verified whether GroupFacade calls Stream with
         * all().
         * - fluent() is used here to use streams.
         * - forEach() is used here to call all(), but any method that
         * works for multiple matches would do. Don't use first() or findFirst().
         */
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Foo')
            ->group('group')
            ->fluent()
            ->forEach(function (MatchedGroup $group) {
                $this->assertEquals('Foo', $group->text());
            });
    }

    /**
     * @test
     */
    public function shouldThrowWithMessage()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");

        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $group->text();
            });
    }

    /**
     * @test
     */
    public function shouldOptionalThrowWithMessage()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group', but it was not matched");

        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $group->orThrow();
            });
    }

    /**
     * @test
     */
    public function shouldGetIdentifier()
    {
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $this->assertEquals('group', $group->usedIdentifier());
            });
    }
}