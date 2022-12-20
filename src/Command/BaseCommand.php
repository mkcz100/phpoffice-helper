<?php

namespace MK\PhpofficeHelper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('hash-password');
        $this->setDescription('Hashes provided password with BCRYPT and prints to output.');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password to be hashed.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $output->writeln('Test test');

        return self::SUCCESS;
    }
}