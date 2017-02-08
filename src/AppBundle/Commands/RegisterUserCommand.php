<?php

namespace AppBundle\Commands;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserCommand extends AbstractCommand
{

    /**
     * @var string
     * @Assert\NotBlank(message="command.register_user_command.username.not_blank")
     */
    public $username;

    /**
     * @var string
     * @Assert\NotBlank(message="command.register_user_command.password.not_blank")
     */
    public $password;

    /**
     * @var string
     * @Assert\Email(message="command.register_user_command.email.invalid")
     */
    public $email;

    /**
     * RegisterUserCommand constructor.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param int $userId
     */
    public function __construct(string $username, string $password, string $email, int $userId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;

        parent::__construct($userId);
    }

    public function getName()
    {
        return "RegisterUser";
    }
}