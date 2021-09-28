<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\LifeTime\LifeTimeList;

interface ConfigProviderInterface
{
    public function getLifeTimes(): LifeTimeList;
}
