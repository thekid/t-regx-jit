<?php
namespace Test\Fakes\CleanRegex\Internal\Model\Match;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class ThrowEntries implements GroupEntries
{
    public function getGroupsTexts(): array
    {
        throw new AssertionError("Failed to assert that GroupEntries wasn't used");
    }

    public function getGroupsOffsets(): array
    {
        throw new AssertionError("Failed to assert that GroupEntries wasn't used");
    }
}
