<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\PollTimeIsUpException;
use Meals\Application\Feature\Poll\UseCase\EmployeePostsChoice\Interactor;
use Meals\Domain\Constraint\TimeRange;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\LifeTime\LifeTime;
use Meals\Domain\Poll\LifeTime\LifeTimeList;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakeConfigProviderInterface;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeePostsChoiceTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $dish = new Dish(42, 'Correct dish', 'Desc');
        $poll = $this->getPoll(true, new DishList([
            $dish
        ]));

        $pollResult = $this->performTestMethod(
            $poll,
            $this->getEmployeeWithPermissions(),
            $dish
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testFailByEmployeeWithNoPermission()
    {
        $this->expectException(AccessDeniedException::class);

        $this->performTestMethod(
            $this->getPoll(true),
            $this->getEmployeeWithNoPermissions(),
            new Dish(0, '', '')
        );
    }

    public function testFailByWrongDateTime()
    {
        $this->expectException(PollTimeIsUpException::class);

        $this->getContainer()->get(FakeConfigProviderInterface::class)->setLifeTimes(
            new LifeTimeList([
                new LifeTime([
                    new TimeRange(new \DateTime('-2 hours'), new \DateTime('-1 hour'))
                ])
            ])
        );

        $dish = new Dish(42, 'Correct dish', 'Desc');
        $poll = $this->getPoll(true, new DishList([
            $dish
        ]));

        $this->performTestMethod(
            $poll,
            $this->getEmployeeWithPermissions(),
            $dish
        );
    }

    public function testFailByInactivePoll()
    {
        $this->expectException(PollIsNotActiveException::class);

        $this->getContainer()->get(FakeConfigProviderInterface::class)->setLifeTimes(null);

        $dish = new Dish(42, 'Correct dish', 'Desc');
        $poll = $this->getPoll(false, new DishList([
            $dish
        ]));

        $this->performTestMethod(
            $poll,
            $this->getEmployeeWithPermissions(),
            $dish
        );
    }

    protected function performTestMethod(Poll $poll, Employee $employee, Dish $dish): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);

        return $this->getContainer()->get(Interactor::class)->postChoice($employee->getId(), $poll->getId(), $dish);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active, ?DishList $dishList = null): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                $dishList ?? new DishList([]),
            )
        );
    }
}
