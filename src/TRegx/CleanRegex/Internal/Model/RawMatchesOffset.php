<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Match;
use function array_key_exists;
use function is_array;
use function is_string;

class RawMatchesOffset implements RawWithGroups, RawMatchesInterface
{
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

    /** @var array */
    private $matches;
    /** @var Base */
    private $subjectable;

    public function __construct(array $matches, Subjectable $subjectable)
    {
        $this->matches = $matches;
        $this->subjectable = $subjectable;
    }

    public function matched(): bool
    {
        return count($this->matches[self::GROUP_WHOLE_MATCH]) > 0;
    }

    /**
     * @return (string|null)[]
     */
    public function getAll(): array
    {
        return array_map(function ($match) {
            list($value, $offset) = $match;
            return $value;
        }, $this->matches[self::GROUP_WHOLE_MATCH]);
    }

    /**
     * @return Match[]
     */
    public function getMatchObjects(): array
    {
        $matchObjects = [];
        foreach ($this->matches[self::GROUP_WHOLE_MATCH] as $index => $match) {
            $matchObjects[] = new Match($this->subjectable, $index, $this);
        }
        return $matchObjects;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->matches);
    }

    public function getLimitedGroupOffsets($nameOrIndex, int $limit)
    {
        return $this->mapToOffset($this->getLimitedGroups($nameOrIndex, $limit));
    }

    private function getLimitedGroups($nameOrIndex, int $limit)
    {
        $match = $this->matches[$nameOrIndex];
        if ($limit === -1) {
            return $match;
        }
        return array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return array_map([$this, 'mapMatch'], $matches);
    }

    public function getText(int $index): string
    {
        list($text, $offset) = $this->matches[self::GROUP_WHOLE_MATCH][$index];
        return $text;
    }

    public function getFirstText(): string
    {
        return $this->getText(self::FIRST_MATCH);
    }

    public function getFirstMatchObject(): Match
    {
        return new Match($this->subjectable, self::FIRST_MATCH, $this);
    }

    private function mapMatch($match): ?int
    {
        if ($match === null || is_string($match)) {
            return null;
        }
        if (!is_array($match)) {
            throw new InternalCleanRegexException();
        }
        list($value, $offset) = $match;
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    public function getOffset(int $index): int
    {
        list($text, $offset) = $this->matches[self::GROUP_WHOLE_MATCH][$index];
        return $offset;
    }

    public function getTextAndOffset(int $index): array
    {
        return $this->matches[self::GROUP_WHOLE_MATCH][$index];
    }

    public function getGroupTextAndOffset($nameOrIndex, int $index): array
    {
        return $this->matches[$nameOrIndex][$index];
    }

    public function getGroupKeys(): array
    {
        return array_keys($this->matches);
    }

    /**
     * @param int $index
     * @return (int|null)[]
     */
    public function getGroupsOffsets(int $index): array
    {
        return array_map(function (array $match) use ($index) {
            list($text, $offset) = $match[$index];
            return $offset;
        }, $this->matches);
    }

    /**
     * @param int $index
     * @return (string|null)[]
     */
    public function getGroupsTexts(int $index): array
    {
        return array_map(function (array $match) use ($index) {
            list($text, $offset) = $match[$index];
            return $text;
        }, $this->matches);
    }

    public function getGroupTexts($group): array
    {
        return array_map(function ($group) {
            list($value, $offset) = $group;
            return $value;
        }, $this->matches[$group]);
    }

    public function isGroupMatched($nameOrIndex, int $index): bool
    {
        return is_array($this->matches[$nameOrIndex][$index]);
    }

    public function getRawMatchOffset(int $index): RawMatchOffset
    {
        return new RawMatchOffset(array_map(function (array $match) use ($index) {
            return $match[$index];
        }, $this->matches));
    }

    public function getRawMatch(int $index): RawMatch
    {
        return new RawMatch(array_map(function (array $match) use ($index) {
            list($text, $offset) = $match[$index];
            return $text;
        }, $this->matches));
    }

    public function filterMatchesByMatchObjects(Predicate $predicate): array
    {
        $matchObjects = $this->getMatchObjects();
        $filteredMatches = array_filter($matchObjects, [$predicate, 'test']);

        return array_map(function (array $match) use ($filteredMatches) {
            return array_values(array_intersect_key($match, $filteredMatches));
        }, $this->matches);
    }
}
