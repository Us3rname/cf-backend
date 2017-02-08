<?php

namespace AppBundle\Commands;

class AddExerciseCategoryCommand extends AbstractCommand
{
    private $exerciseCategoryName;

    /**
     * AddExerciseCommand constructor.
     * @param string $exerciseCategoryName
     * @param int $userId
     */
    public function __construct(string $exerciseCategoryName, int $userId)
    {
        $this->exerciseCategoryName = $exerciseCategoryName;
        parent::__construct($userId);
    }

    /**
     * @return string
     */
    public function getExerciseCategoryName(): string
    {
        return $this->exerciseCategoryName;
    }

    public function getName()
    {
        return 'ÁddExerciseCategory';
    }
}