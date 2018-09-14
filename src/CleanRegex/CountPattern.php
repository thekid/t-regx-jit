<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;

class CountPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }
}
