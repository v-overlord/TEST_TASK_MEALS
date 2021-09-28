<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\ConfigProviderInterface;
use Meals\Domain\Constraint\DayOfTheWeek;
use Meals\Domain\Constraint\TimeRange;
use Meals\Domain\Poll\LifeTime\LifeTime;
use Meals\Domain\Poll\LifeTime\LifeTimeList;

class FakeConfigProviderInterface implements ConfigProviderInterface
{
    public $lifeTimes = null;

    public function setDefaultLifeTimes(): LifeTimeList {
        $this->lifeTimes = new LifeTimeList([
            new LifeTime([
                new DayOfTheWeek((int) (new \DateTimeImmutable)->format('N')),
                new TimeRange(
                    new \DateTime('-1 hour'),
                    new \DateTime('+1 hour'))
            ])
        ]);

        return $this->lifeTimes;
    }

    public function getLifeTimes(): LifeTimeList
    {
        return $this->lifeTimes ?? $this->setDefaultLifeTimes();
    }

    public function setLifeTimes(?LifeTimeList $lifeTimeList): void
    {
        $this->lifeTimes = $lifeTimeList;
    }
}
