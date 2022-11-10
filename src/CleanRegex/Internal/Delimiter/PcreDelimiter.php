<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;

class PcreDelimiter
{
    /** @var string */
    public $delimiter;

    public function __construct(string $delimiter)
    {
        if ($this->legalDelimiter($delimiter)) {
            $this->delimiter = $delimiter;
        } else {
            throw MalformedPcreTemplateException::invalidDelimiter($delimiter);
        }
    }

    private function legalDelimiter(string $delimiter): bool
    {
        if (\in_array($delimiter, ["\0", "\t", "\n", "\v", "\f", "\r", ' ', "\\", '(', '[', '{', '<'], true)) {
            return false;
        }
        if (\ctype_alnum($delimiter)) {
            return false;
        }
        if (\ord($delimiter) > 127) {
            return false;
        }
        return true;
    }

    public function patternAndFlags(string $pcre): array
    {
        return $this->separatedAtPosition($pcre, $this->closingDelimiterPosition($pcre));
    }

    private function separatedAtPosition(string $pcre, int $closingDelimiterPosition): array
    {
        $pattern = \substr($pcre, 0, $closingDelimiterPosition);
        $modifiers = \substr($pcre, $closingDelimiterPosition + 1);
        return [$pattern, $modifiers];
    }

    private function closingDelimiterPosition(string $pcre): int
    {
        $position = \strrpos($pcre, $this->delimiter);
        if ($position === false) {
            throw MalformedPcreTemplateException::unclosed($this->delimiter);
        }
        return $position;
    }
}
