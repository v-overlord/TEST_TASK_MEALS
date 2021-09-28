<?php
namespace Meals\Domain\Constraint;

interface Constraint {
    public function validate(\DateTime &$dateTime): bool;
}