<?php

namespace MK\PhpofficeHelper\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TitleCellCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('title-cell');
        $this->setDescription('Adds title cell in every worksheet and exports new XLS file to output folder.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $output->writeln('Test test');

        return self::SUCCESS;
    }
}