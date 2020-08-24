<?php

namespace LDL\Http\Api\Console\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CreateApplicationCommand extends SymfonyCommand {

    protected function configure()
    {
        $this->setName('api:application:create')
            ->setDescription('Create a new application');

    }

}