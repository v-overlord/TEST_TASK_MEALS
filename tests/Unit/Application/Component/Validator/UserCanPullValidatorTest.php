<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollTimeIsUpException;
use Meals\Application\Component\Validator\UserCanPollValidator;
use Meals\Domain\Constraint\TimeRange;
use Meals\Domain\DayOfTheWeek\DayOfTheWeek;
use Meals\Domain\Poll\LifeTime\LifeTime;
use Meals\Domain\Poll\LifeTime\LifeTimeList;
use PHPUnit\Framework\TestCase;

class UserCanPullValidatorTest extends TestCase
{
    public function testSuccessful()
    {
        // Allow access only on Friday between 6 am and 10 pm
        $constraints = [
            new \Meals\Domain\Constraint\DayOfTheWeek(DayOfTheWeek::FRIDAY),
            new TimeRange(new \DateTime('06:00 AM'), new \DateTime('10:00 PM'))
        ];

        $this->performTestMethod($constraints);
    }

    public function testFailByDayOfTheWeek()
    {
        $this->expectException(PollTimeIsUpException::class);

        $constraints = [
            new \Meals\Domain\Constraint\DayOfTheWeek(DayOfTheWeek::TUESDAY),
            new TimeRange(new \DateTime('06:00 AM'), new \DateTime('10:00 PM'))
        ];

        $this->performTestMethod($constraints);
    }

    public function testFailByTime()
    {
        $this->expectException(PollTimeIsUpException::class);

        $constraints = [
            new \Meals\Domain\Constraint\DayOfTheWeek(DayOfTheWeek::FRIDAY),
            new TimeRange(new \DateTime('07:00 AM'), new \DateTime('11:00 PM'))
        ];

        $this->performTestMethod($constraints);
    }

    protected function performTestMethod(array $constraints): void
    {
        $lifeTimeList = new LifeTimeList([
            new LifeTime($constraints)
        ]);
        $time = $this->getDataTime();
        $validator = new UserCanPollValidator();

        verify($validator->validate($lifeTimeList, $time))->null();
    }

    protected function getDataTime(): \DateTime
    {
        // 1.1.2021 is Friday
        return new \DateTime('01-01-2021 06:00 AM');
    }
}
