<?php

namespace Meals\Domain\Poll\LifeTime;

/*
 * There is may be the cron-like configuration, but YAGNI doesn't allow it
 */

use Assert\Assertion;
use Meals\Domain\Constraint\Constraint;

class LifeTime
{
    /** @var Constraint[] **/
    private $constraints = [];

    public function __construct($constraints) {
        Assertion::allIsInstanceOf($constraints, Constraint::class);

        $this->constraints = $constraints;
    }

    public function validate(\DateTime &$dateTime): bool
    {
        foreach($this->constraints as $constraint) {
            if ($constraint->validate($dateTime) === false) {
                return false;
            }
        }

        return true;
    }
}