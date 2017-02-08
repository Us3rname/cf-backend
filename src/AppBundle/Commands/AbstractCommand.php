<?php

namespace AppBundle\Commands;

abstract class AbstractCommand implements Command
{

    protected $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     * @return AbstractCommand
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

}