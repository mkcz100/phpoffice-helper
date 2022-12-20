<?php

namespace MK\PhpofficeHelper\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class XlsToPdfCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('xls-to-pdf');
        $this->setDescription('Converts XLS files from input folder to PDF in output.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $output->writeln('Test test');

        return self::SUCCESS;
    }
}