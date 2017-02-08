<?php

namespace AppBundle\DTO;

class CrossfitUser
{

    public $id;

    public $firstName;

    public $lastName;

    public $email;

    /**
     * CrossfitUser constructor.
     * @param $firstName
     * @param $lastName
     * @param $email
     */
    public function __construct($id, $firstName, $lastName, $email)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
}
