<?php

namespace AppBundle\Entity;

use AppBundle\Commands\Command;
use Doctrine\ORM\Mapping as ORM;

/**
 * Exercise
 *
 * @ORM\Table(name="command_log")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandLogRepository")
 */
class CommandLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="command_name", type="string", length=255)
     */
    private $commandName;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="object", length=65535)
     */
    private $command;

    /**
     * @ORM\Column(type="datetime", name="created")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="CrossfitUser")
     * @var CrossfitUser
     */
    protected $executedBy;

    /**
     * @ORM\PrePersist()
     */
    public function executeOnPrePersist()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @param string $commandName
     * @return CommandLog
     */
    public function setCommandName(string $commandName): CommandLog
    {
        $this->commandName = $commandName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param Command $command
     * @return CommandLog
     */
    public function setCommand(Command $command): CommandLog
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return CrossfitUser
     */
    public function getExecutedBy(): CrossfitUser
    {
        return $this->executedBy;
    }

    /**
     * @param CrossfitUser $executedBy
     * @return CommandLog
     */
    public function setExecutedBy(CrossfitUser $executedBy): CommandLog
    {
        $this->executedBy = $executedBy;
        return $this;
    }

}