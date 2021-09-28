<?php
namespace Meals\Domain\Constraint;

use DateTime;

class TimeRange implements Constraint
{
    /** @var DateTime **/
    private $start;

    /** @var DateTime **/
    private $end;

    public function __construct(DateTime $start, DateTime $end)
    {
        $this->start = $start->format('U') % 86400;
        $this->end = $end->format('U') % 86400;
    }

    public function validate(DateTime &$dateTime): bool
    {
        $currentTime = $dateTime->format('U') % 86400;
        return $this->start <= $currentTime && $currentTime <= $this->end;
    }
}