<?php

namespace AppBundle\Service;

use AppBundle\Repository\ExerciseRepository;

class CreateExercise
{

    private $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function createExercise($name)
    {
        return $this->exerciseRepository->createExercise($name);
    }

}