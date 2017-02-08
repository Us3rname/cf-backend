<?php

namespace AppBundle\Commands;
use AppBundle\Entity\CrossfitUser;

class AddExerciseCommand extends AbstractCommand
{

    private $exerciseName;
    private $exerciseCategoryId;

    /**
     * AddExerciseCommand constructor.
     * @param string $exerciseName
     * @param int $exerciseCategoryId
     * @param int $userId
     */
    public function __construct(string $exerciseName, int $exerciseCategoryId, int $userId)
    {
        $this->exerciseName = $exerciseName;
        $this->exerciseCategoryId = $exerciseCategoryId;
        parent::__construct($userId);
    }

    /**
     * @return string
     */
    public function getExerciseName(): string
    {
        return $this->exerciseName;
    }

    /**
     * @return int
     */
    public function getExerciseCategoryId(): int
    {
        return $this->exerciseCategoryId;
    }

    public function getName()
    {
        return 'ÁddExercise';
    }
}