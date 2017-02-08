<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserRegistered extends Event
{
    const NAME = 'user.registered';

    private $userId;

    /**
     * UserRegistered constructor.
     * @param $userId
     */
    public function __construct($userId)
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

}