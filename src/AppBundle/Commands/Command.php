<?php

namespace AppBundle\Commands;


interface Command
{
    public function getName();
    public function getUserId();
}