<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\SavePollResultProviderInterface;
use Meals\Domain\Poll\PollResult;

class FakeSavePollResultProviderInterface implements SavePollResultProviderInterface
{
    public function saveResult(PollResult $pollResult): bool
    {
        return true;
    }
}
