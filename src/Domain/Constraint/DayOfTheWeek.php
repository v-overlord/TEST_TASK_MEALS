<?php
namespace Meals\Domain\Constraint;

class DayOfTheWeek implements Constraint
{
    /** @var int **/
    private $day;

    public function __construct(int $day)
    {
        if ($day < 0 || $day > 7) {
            throw new \RangeException('Day must be in [1, 7]');
        }

        $this->day = $day;
    }

    public function validate(\DateTime &$dateTime): bool
    {
        return ((int) $dateTime->format('N')) === $this->day;
    }
}