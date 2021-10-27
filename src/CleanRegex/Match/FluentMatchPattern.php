<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\FilterStream;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntegerStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeyStream;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\UniqueStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\Stream\ValuesStream;
use TRegx\CleanRegex\Internal\Message\Stream\FromFirstStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\FromNthStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

class FluentMatchPattern implements \Countable, \IteratorAggregate
{
    /** @var Upstream */
    private $stream;
    /** @var Subject */
    private $subject;

    public function __construct(Upstream $stream, Subject $subject)
    {
        $this->stream = $stream;
        $this->subject = $subject;
    }

    public function all(): array
    {
        try {
            return $this->stream->all();
        } catch (UnmatchedStreamException $exception) {
            return [];
        }
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->all(), 0, $limit);
    }

    /**
     * @param callable|null $consumer
     * @return mixed
     */
    public function first(callable $consumer = null)
    {
        return $this->findFirst($consumer ?? static function ($argument) {
                return $argument;
            })
            ->orThrow();
    }

    public function findFirst(callable $consumer): Optional
    {
        try {
            $firstElement = $this->stream->first();
        } catch (StramRejectedException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchElementFluentException::class, $exception->notMatchedMessage()));
        } catch (EmptyStreamException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchElementFluentException::class, new FromFirstStreamMessage()));
        }
        return new PresentOptional($consumer($firstElement));
    }

    public function nth(int $index)
    {
        return $this->findNth($index)->orThrow();
    }

    public function findNth(int $index): Optional
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative index: $index");
        }
        try {
            $elements = \array_values($this->stream->all());
        } catch (UnmatchedStreamException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchElementFluentException::class, new SubjectNotMatched\FromNthStreamMessage($index)));
        }
        if (!\array_key_exists($index, $elements)) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchElementFluentException::class, new FromNthStreamMessage($index, \count($elements))));
        }
        return new PresentOptional($elements[$index]);
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->all() as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function count(): int
    {
        return \count($this->all());
    }

    public function getIterator(): \Iterator
    {
        try {
            return new \ArrayIterator($this->stream->all());
        } catch (UnmatchedStreamException $exception) {
            return new \EmptyIterator();
        }
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(new MapStream($this->stream, $mapper));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMapStream($this->stream, new ArrayMergeStrategy(), new FlatFunction($mapper, 'flatMap')));
    }

    public function flatMapAssoc(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMapStream($this->stream, new AssignStrategy(), new FlatFunction($mapper, 'flatMapAssoc')));
    }

    public function distinct(): FluentMatchPattern
    {
        return $this->next(new UniqueStream($this->stream));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(new FilterStream($this->stream, new Predicate($predicate, 'filter')));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(new ValuesStream($this->stream));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(new KeyStream($this->stream));
    }

    public function asInt(int $base = null): FluentMatchPattern
    {
        return $this->next(new IntegerStream($this->stream, new Number\Base($base)));
    }

    public function groupByCallback(callable $groupMapper): FluentMatchPattern
    {
        return $this->next(new GroupByCallbackStream($this->stream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    private function next(Upstream $stream): FluentMatchPattern
    {
        return new FluentMatchPattern($stream, $this->subject);
    }
}
