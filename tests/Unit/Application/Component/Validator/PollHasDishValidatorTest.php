<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollDoesNotContainDishException;
use Meals\Application\Component\Validator\PollHasDishValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use PHPUnit\Framework\TestCase;

class PollHasDishValidatorTest extends TestCase
{
    public function testSuccessful()
    {
        $dish = new Dish(0, 'Napalm', 'I love the smell of napalm in the morning');

        $poll = new Poll(
            0,
            true,
            new Menu(
                0,
                'Menu',
                new DishList([
                    $dish
                ])
            )
        );

        $validator = new PollHasDishValidator();

        verify($validator->validate($poll, $dish))->null();
    }

    public function testFail()
    {
        $this->expectException(PollDoesNotContainDishException::class);

        $dish = new Dish(0, 'Napalm', 'I love the smell of napalm in the morning');

        $poll = new Poll(
            0,
            true,
            new Menu(
                0,          // <----
                'Menu',
                new DishList([
                    new Dish(
                        42, // <----
                        'Title',
                        'Desc')
                ])
            )
        );

        $validator = new PollHasDishValidator();

        $validator->validate($poll, $dish);
    }
}
