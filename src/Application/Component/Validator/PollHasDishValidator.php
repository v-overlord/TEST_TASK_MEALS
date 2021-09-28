<?php
namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollDoesNotContainDishException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Poll\Poll;

class PollHasDishValidator
{
    public function validate(Poll $poll, Dish $dish): void
    {
        if ($poll->getMenu()->getDishes()->hasDish($dish) === false) {
            throw new PollDoesNotContainDishException();
        }

    }
}
