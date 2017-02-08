<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 4-1-2017
 * Time: 22:11
 */

namespace AppBundle\CommandHandler;


use AppBundle\Commands\Command;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Entity\Exercise;
use AppBundle\Entity\Unit;
use AppBundle\Exception\ExerciseNotFoundException;
use AppBundle\Exception\UnitNotFoundException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Repository\CrossfitUserRepository;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\Repository\ExerciseResultRepository;
use AppBundle\Repository\UnitRepository;

class LogResultHandler implements Handler
{

    private $userRepository;
    private $unitRepository;
    private $exerciseRepository;
    private $exerciseResultRepository;

    /**
     * LogResultHandler constructor.
     * @param CrossfitUserRepository $userRepository
     * @param UnitRepository $unitRepository
     * @param ExerciseRepository $exerciseRepository
     */
    public function __construct(CrossfitUserRepository $userRepository,
                                UnitRepository $unitRepository,
                                ExerciseRepository $exerciseRepository,
                                ExerciseResultRepository $exerciseResultRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->unitRepository = $unitRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseResultRepository = $exerciseResultRepository;
    }


    public function handle(Command $command)
    {
        /** @var CrossfitUser $user */
        $user = $this->userRepository->findOneBy(['id' => $command->getUserId()]);

        if (empty($user)) {
            throw new UserNotFoundException('Gebruiker kan niet gevonden worden.');
        }

        /** @var Unit $unit */
        $unit = $this->unitRepository->findOneBy(['id' => $command->getUnitId()]);

        if (empty($unit)) {
            throw new UnitNotFoundException('Unit kan niet gevonden worden.');
        }

        /** @var Exercise $exercise */
        $exercise = $this->exerciseRepository->findOneBy(['id'=> $command->getExerciseId()]);

        if (empty($exercise)) {
            throw new ExerciseNotFoundException('Exercise kan niet gevonden worden.');
        }

        $this->exerciseResultRepository->logResult($command->getResult(),$user, $exercise, $unit);
    }
}