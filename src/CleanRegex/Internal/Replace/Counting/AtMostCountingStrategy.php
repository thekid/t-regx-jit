<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;

class AtMostCountingStrategy implements CountingStrategy
{
    /** @var Exceed */
    private $exeed;
    /** @var int */
    private $limit;
    /** @var string */
    private $limitPhrase;

    public function __construct(Definition $definition, Subject $subject, int $limit, string $phrase)
    {
        $this->exeed = new Exceed($definition, $subject);
        $this->limit = $limit;
        $this->limitPhrase = $phrase;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        if ($this->exeed->exeeds($this->limit)) {
            throw ReplacementExpectationFailedException::superfluous($this->limit, $this->limitPhrase);
        }
    }
}
