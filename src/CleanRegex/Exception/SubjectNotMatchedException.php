<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupOffsetSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstTripleSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstTupleSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthMatchMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subjectable;

class SubjectNotMatchedException extends \Exception implements PatternException
{
    /** @var string */
    private $subject; // Debugger

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public static function forFirst(Subjectable $subjectable): self
    {
        return self::withMessage(new FirstMatchMessage(), $subjectable);
    }

    public static function forNth(Subjectable $subjectable, int $index): self
    {
        return self::withMessage(new NthMatchMessage($index), $subjectable);
    }

    public static function forNthGroup(Subjectable $subjectable, GroupKey $group, int $index): self
    {
        return self::withMessage(new NthGroupMessage($group, $index), $subjectable);
    }

    public static function forFirstGroupOffset(Subjectable $subjectable, GroupKey $group): self
    {
        return self::withMessage(new FirstGroupOffsetSubjectMessage($group), $subjectable);
    }

    public static function forFirstGroup(Subjectable $subjectable, GroupKey $group): self
    {
        return self::withMessage(new FirstGroupSubjectMessage($group), $subjectable);
    }

    public static function forFirstTuple(Subjectable $subjectable, GroupKey $group1, GroupKey $group2): self
    {
        throw SubjectNotMatchedException::withMessage(new FirstTupleSubjectMessage($group1, $group2), $subjectable);
    }

    public static function forFirstTriple(Subjectable $subjectable, GroupKey $group1, GroupKey $group2, GroupKey $group3): self
    {
        throw SubjectNotMatchedException::withMessage(new FirstTripleSubjectMessage($group1, $group2, $group3), $subjectable);
    }

    public static function withMessage(NotMatchedMessage $message, Subjectable $subjectable): self
    {
        return new SubjectNotMatchedException($message->getMessage(), $subjectable->getSubject());
    }
}
