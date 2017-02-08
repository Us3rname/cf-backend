<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 7-1-2017
 * Time: 21:05
 */

namespace AppBundle\CommandHandler;

use AppBundle\Commands\AddExerciseCategoryCommand;
use AppBundle\Commands\Command;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Repository\CrossfitUserRepository;
use AppBundle\Repository\ExerciseCategoryRepository;

class AddExerciseCategoryHandler implements Handler
{

    private $exerciseCategoryRepository;
    private $crossfitUserRepository;

    /**
     * AddExerciseCategoryHandler constructor.
     * @param $exerciseCategoryRepository
     */
    public function __construct(ExerciseCategoryRepository $exerciseCategoryRepository, CrossfitUserRepository $crossfitUserRepository)
    {
        $this->exerciseCategoryRepository = $exerciseCategoryRepository;
        $this->crossfitUserRepository = $crossfitUserRepository;
    }


    public function handle(Command $command)
    {

        /** @var CrossfitUser $crossfitUser */
        $crossfitUser = $this->crossfitUserRepository->findOneBy(['id' => $command->getUserId()]);
        $this->exerciseCategoryRepository->createExerciseCategory($command->getExerciseCategoryName(), $crossfitUser);
    }
}