<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeePostsChoice;

use Meals\Application\Component\Provider\ConfigProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\SavePollResultProviderInterface;
use Meals\Application\Component\Validator\PollHasDishValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserCanPollValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var SavePollResultProviderInterface */
    private $savePollResultProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var ConfigProviderInterface */
    private $configProvider;

    /** @var UserCanPollValidator */
    private $userCanPullValidator;

    /** @var UserHasAccessToViewPollsValidator */
    private $userHasAccessToPollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /** @var PollHasDishValidator */
    private $pollHasDishValidator;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param SavePollResultProviderInterface $savePollResultProvider
     * @param PollProviderInterface $pollProvider
     * @param ConfigProviderInterface $configProvider
     * @param UserCanPollValidator $userCanPullValidator
     * @param UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        SavePollResultProviderInterface $savePollResultProvider,
        PollProviderInterface $pollProvider,
        ConfigProviderInterface $configProvider,

        UserCanPollValidator $userCanPullValidator,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        PollHasDishValidator $pollHasDishValidator
    ) {
        $this->employeeProvider       = $employeeProvider;
        $this->savePollResultProvider = $savePollResultProvider;
        $this->pollProvider           = $pollProvider;
        $this->configProvider         = $configProvider;

        $this->userCanPullValidator          = $userCanPullValidator;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator         = $pollIsActiveValidator;
        $this->pollHasDishValidator          = $pollHasDishValidator;
    }

    /**
     * @param int $employeeId
     * @param int $pollId
     * @param Dish $dish
     * @return PollResult
     */
    public function postChoice(int $employeeId, int $pollId, Dish $dish): PollResult
    {
        $employee  = $this->employeeProvider->getEmployee($employeeId);
        $lifetimes = $this->configProvider->getLifeTimes();
        $poll      = $this->pollProvider->getPoll($pollId);

        // --- VALIDATORS
        // If this user has access to the pull...
        $this->userHasAccessToPollsValidator->validate($employee->getUser());
        // If this user can pull...
        $this->userCanPullValidator->validate($lifetimes);
        // If this poll is active...
        $this->pollIsActiveValidator->validate($poll);
        // If this poll has the dish...
        $this->pollHasDishValidator->validate($poll, $dish);

        // $id - $pollID? $poll->getId() contains it...
        // $employeeFloor contains in the $employee object...
        $pollResult = new PollResult($pollId, $poll, $employee, $dish, $employee->getFloor());

        $this->savePollResultProvider->saveResult($pollResult);

        return $pollResult;
    }
}
