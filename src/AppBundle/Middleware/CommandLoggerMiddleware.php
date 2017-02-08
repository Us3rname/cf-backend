<?php

namespace AppBundle\Middleware;

use AppBundle\Commands\Command;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Repository\CommandLogRepository;
use AppBundle\Repository\CrossfitUserRepository;
use League\Tactician\Middleware;

class CommandLoggerMiddleware implements Middleware
{

    private $commandLogRepository;
    private $crossfitRepository;

    public function __construct(CommandLogRepository $commandLogRepository, CrossfitUserRepository $crossfitUserRepository)
    {
        $this->commandLogRepository = $commandLogRepository;
        $this->crossfitRepository = $crossfitUserRepository;
    }

    /**
     * @param Command $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        /** @var CrossfitUser $crossfitUser */
        $crossfitUser = $this->crossfitRepository->findOneBy(['id' => $command->getUserId()]);
        $this->commandLogRepository->createCommandLogEntry($command, $crossfitUser);

        return $next($command);
    }
}