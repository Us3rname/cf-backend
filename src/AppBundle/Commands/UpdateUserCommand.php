<?php

namespace AppBundle\Commands;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserCommand extends AbstractCommand
{

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $emailaddress;

    /**
     * UpdateUserCommand constructor.
     * @param string $firstname
     * @param string $lastname
     */
    public function __construct(int $userId, string $firstname, string $lastname, string $emailadress)
    {
        parent::__construct($userId);

        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->emailaddress = $emailadress;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getEmailaddress(): string
    {
        return $this->emailaddress;
    }

    public function getName()
    {
        return "UpdateUser";
    }
}