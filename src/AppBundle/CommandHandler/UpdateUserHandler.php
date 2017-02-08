<?php

namespace AppBundle\CommandHandler;

use AppBundle\Commands\Command;
use AppBundle\Commands\RegisterUserCommand;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Event\SetACLRightsEvent;
use AppBundle\Event\UserRegistered;
use AppBundle\Exception\UserAlreadyExistsException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Repository\CrossfitUserRepository;
use FOS\UserBundle\Doctrine\UserManager;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcherInterface;

class UpdateUserHandler implements Handler
{

    private $crossfitUserRepository;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserManager $userManager
     * @param TraceableEventDispatcherInterface $eventDispatcher
     */
    public function __construct(CrossfitUserRepository $crossfitUserRepository
    )
    {
        $this->crossfitUserRepository = $crossfitUserRepository;
    }

    public function handle(Command $command)
    {

        /** @var CrossfitUser $cfUser */
        $cfUser = $this->crossfitUserRepository->find($command->getUserId());

        if (empty($cfUser)) {
            throw new UserNotFoundException('Crossfit user niet gevonden.');
        }


        $cfUser->getProfile()->setFirstname($command->getFirstname());
        $cfUser->getProfile()->setLastname($command->getLastname());
        $this->crossfitUserRepository->saveUser($cfUser);
    }
}