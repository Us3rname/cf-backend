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
use AppBundle\Exception\ExerciseCategoryNotFoundException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Repository\CrossfitUserRepository;
use AppBundle\Repository\ExerciseCategoryRepository;
use AppBundle\Repository\ExerciseRepository;

class AddExerciseHandler implements Handler
{

    private $exerciseRepository;
    private $exerciseCategoryRepository;
    private $crossfitUserRepository;

    /**
     * AddExerciseCategoryHandler constructor.
     * @param $exerciseRepository
     * @param $crossfitUserRepository
     * @param $exerciseCategoryRepository
     */
    public function __construct(ExerciseRepository $exerciseRepository, CrossfitUserRepository $crossfitUserRepository,
                                ExerciseCategoryRepository $exerciseCategoryRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseCategoryRepository = $exerciseCategoryRepository;
        $this->crossfitUserRepository = $crossfitUserRepository;
    }


    public function handle(Command $command)
    {

        /** @var CrossfitUser $crossfitUser */
        $crossfitUser = $this->crossfitUserRepository->findOneBy(['id' => $command->getUserId()]);

        if (empty($crossfitUser)) {
            throw new UserNotFoundException("Gebruiker niet gevonden.");
        }

        $exerciseCategory = $this->exerciseCategoryRepository->findOneBy(['id' => $command->getExerciseCategoryId()]);

        if (empty($exerciseCategory)) {
            throw new ExerciseCategoryNotFoundException("Exercise categorie niet gevonden.");
        }

        $this->exerciseRepository->createExercise($command->getExerciseName(),$exerciseCategory,  $crossfitUser);
    }
}