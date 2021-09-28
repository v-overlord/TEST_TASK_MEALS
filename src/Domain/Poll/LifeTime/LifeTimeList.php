<?php

namespace Meals\Domain\Poll\LifeTime;

use Assert\Assertion;

class LifeTimeList
{
    /** @var LifeTime[] */
    private $lifetimes;

    /**
     * LifeTimeList constructor.
     * @param LifeTime[] $lifetimes
     */
    public function __construct(array $lifetimes)
    {
        Assertion::allIsInstanceOf($lifetimes, LifeTime::class);
        $this->lifetimes = $lifetimes;
    }

    /**
     * @return LifeTime[]
     */
    public function getLifeTimes(): array
    {
        return $this->lifetimes;
    }

    public function hasAcceptableLifeTime(\DateTime $dateTime): bool
    {
        foreach ($this->lifetimes as $lifeTime) {
            if ($lifeTime->validate($dateTime)) {
                return true;
            }
        }

        return false;
    }
}
