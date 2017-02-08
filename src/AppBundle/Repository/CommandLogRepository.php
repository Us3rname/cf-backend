<?php

namespace AppBundle\Repository;

use AppBundle\Commands\Command;
use AppBundle\Entity\CommandLog;
use AppBundle\Entity\CrossfitUser;

class CommandLogRepository extends \Doctrine\ORM\EntityRepository
{

    public function createCommandLogEntry(Command $command, CrossfitUser $crossfitUser)
    {

        $commandLog = new CommandLog();
        $commandLog
            ->setCommand($command)
            ->setCommandName($command->getName())
            ->setExecutedBy($crossfitUser)
        ;

        $this->getEntityManager()->persist($commandLog);
    }

}