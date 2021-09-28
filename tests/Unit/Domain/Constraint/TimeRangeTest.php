<?php

namespace tests\Meals\Unit\Domain\Constraint;

use Meals\Domain\Constraint\TimeRange;
use PHPUnit\Framework\TestCase;

class TimeRangeTest extends TestCase
{
    public function testSuccessful()
    {
        $dateTime = new \DateTime();
        $constraint = new TimeRange(
            new \DateTime('-1 hour'),
            new \DateTime('+1 hour')
        );

        $this->assertTrue($constraint->validate($dateTime));
    }

    public function testFail()
    {
        $dateTime = new \DateTime();
        $constraint = new TimeRange(
            new \DateTime('-2 hour'),
            new \DateTime('-1 hour')
        );

        $this->assertFalse($constraint->validate($dateTime));
    }
}