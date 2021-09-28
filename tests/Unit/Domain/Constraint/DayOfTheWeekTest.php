<?php

namespace tests\Meals\Unit\Domain\Constraint;

use Meals\Domain\Constraint\DayOfTheWeek;
use PHPUnit\Framework\TestCase;

class DayOfTheWeekTest extends TestCase
{
    public function testSuccessful()
    {
        $dateTime = new \DateTime();
        $constraint = new DayOfTheWeek((int) $dateTime->format('N'));

        $this->assertTrue($constraint->validate($dateTime));
    }

    public function testFail()
    {
        $dateTime = new \DateTime();
        $currentDayOfTheWeek = ((int) $dateTime->format('N')) + 1;

        if ($currentDayOfTheWeek > 7) {
            $currentDayOfTheWeek = 1;
        }

        $constraint = new DayOfTheWeek($currentDayOfTheWeek);

        $this->assertFalse($constraint->validate($dateTime));
    }

    public function testIncorrectDayOfTheWeek()
    {
        $this->expectException(\RangeException::class);

        new DayOfTheWeek(42);
    }
}