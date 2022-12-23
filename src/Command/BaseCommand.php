<?php

namespace MK\PhpofficeHelper\Command;

use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('base-command');
        $this->setDescription('Base command class.');
    }
}