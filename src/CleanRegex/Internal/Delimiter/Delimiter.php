<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Delimiter
{
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function delimited(PatternAutoCapture $autoCapture, Phrase $phrase, Flags $flags): string
    {
        return $this->patternDelimited($autoCapture, $this->verbedPattern($phrase), $flags);
    }

    private function verbedPattern(Phrase $phrase): VerbedPattern
    {
        return new VerbedPattern($phrase->conjugated($this->delimiter));
    }

    private function patternDelimited(PatternAutoCapture $autoCapture, VerbedPattern $pattern, Flags $flags): string
    {
        return $this->delimiter
            . $pattern->verbs()
            . $autoCapture->patternOptionSetting($flags)
            . $pattern->expression()
            . $this->delimiter
            . $autoCapture->patternModifiers($flags);
    }
}
