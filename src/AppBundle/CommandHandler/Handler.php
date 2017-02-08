<?php

namespace AppBundle\CommandHandler;

use AppBundle\Commands\Command;

interface Handler
{
    public function handle(Command $command);
}