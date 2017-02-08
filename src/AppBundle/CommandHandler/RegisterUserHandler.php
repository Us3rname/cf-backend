<?php

namespace AppBundle\CommandHandler;

use AppBundle\Commands\Command;
use AppBundle\Commands\RegisterUserCommand;
use AppBundle\Event\SetACLRightsEvent;
use AppBundle\Event\UserRegistered;
use AppBundle\Exception\UserAlreadyExistsException;
use AppBundle\Repository\CrossfitUserRepository;
use FOS\UserBundle\Doctrine\UserManager;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcherInterface;

class RegisterUserHandler implements Handler
{

    private $userManager;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    private $crossfitUserRepository;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserManager $userManager
     * @param TraceableEventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserManager $userManager,
                                TraceableEventDispatcherInterface $eventDispatcher,
                                CrossfitUserRepository $crossfitUserRepository
    )
    {
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->crossfitUserRepository = $crossfitUserRepository;
    }

    public function handle(Command $command)
    {


        $cfUser = $this->crossfitUserRepository->findOneBy(['email' => $command->email]);

        if (!empty($cfUser)) {
            throw new UserAlreadyExistsException('Er bestaat al een gebruiker met dit emailadres.');
        }

        $user = $this->userManager->createUser();
        $user->setUsername($command->email);
        $user->setEmail($command->email);
        $user->setPassword(uniqid('tmp_'));
        $this->userManager->updateUser($user);

        $userRegistered = new UserRegistered($user->getId());
        $this->eventDispatcher->dispatch(UserRegistered::NAME, $userRegistered);
    }
}