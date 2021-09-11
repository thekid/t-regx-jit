<?php
namespace Test\Utils;

use AssertionError;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Expression\Standard;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class Definitions
{
    public static function pattern(string $pattern, string $flags = null): Definition
    {
        if (TrailingBackslash::hasTrailingSlash($pattern)) {
            throw new AssertionError();
        }
        /**
         * I intentionally not use {@see Standard}, because if there are bugs in it,
         * then the tests are compromised. By using low-level {@see Delimiter} and
         * {@see PatternWord} to reduce the posibilities of false-positives in tests.
         */
        return new Definition(Delimiter::suitable($pattern)->delimited(new PatternWord($pattern), new Flags($flags ?? '')), $pattern);
    }

    public static function pcre(string $pattern): Definition
    {
        return new Definition($pattern, $pattern);
    }
}