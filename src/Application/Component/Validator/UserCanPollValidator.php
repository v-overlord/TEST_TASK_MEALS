<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollTimeIsUpException;
use Meals\Domain\Poll\LifeTime\LifeTimeList;


class UserCanPollValidator
{
    public function validate(LifeTimeList $lifeTimes, ?\DateTime $dateTime = null): void
    {
        $dateTime = $dateTime ?? new \DateTime();

        if (!$lifeTimes->hasAcceptableLifeTime($dateTime)) {
            throw new PollTimeIsUpException();
        }
    }
}
