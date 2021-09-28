<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\PollResult;

interface SavePollResultProviderInterface
{
    public function saveResult(PollResult $pollResult): bool;
}
