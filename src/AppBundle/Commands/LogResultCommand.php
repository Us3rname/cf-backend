<?php

namespace AppBundle\Commands;

use AppBundle\Entity\CrossfitUser;

class LogResultCommand extends AbstractCommand
{

    private $userId;
    private $exerciseId;
    private $unitId;
    private $result;

    /**
     * LogResultCommand constructor.
     * @param int $userId
     * @param int $exerciseId
     * @param int $unitId
     * @param $crossfitUser
     */
    public function __construct(int $userId, int $exerciseId, int $unitId, double $result, CrossfitUser $crossfitUser)
    {
        $this->userId = $userId;
        $this->exerciseId = $exerciseId;
        $this->unitId = $unitId;
        $this->result = $result;
        $this->userId = $crossfitUser;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getExerciseId()
    {
        return $this->exerciseId;
    }

    /**
     * @return mixed
     */
    public function getUnitId()
    {
        return $this->unitId;
    }


    public function getName()
    {
        return 'LogResultCommand';
    }
}