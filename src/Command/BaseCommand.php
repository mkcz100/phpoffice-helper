<?php

namespace MK\PhpofficeHelper\Command;

use Exception;
use SplFileInfo;
use MK\PhpofficeHelper\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    protected string $outputDir;
    protected string $inputDir;
    abstract protected function processFile(SplFileInfo $file): void;

    protected function configure()
    {
        $this->setName('base-command');
        $this->setDescription('Base command class.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $this->inputDir = Util::buildDir('/input');
        $this->outputDir = Util::buildDir('/output');

        $files = Util::loadDirectory($this->inputDir);

        $validFiles = [];
        $invalidFiles = [];

        foreach ($files as $file) {
            try {
                $this->processFile($file);

                $validFiles[] = $file;
            } catch (Exception $e) {
                $output->writeln($e->getMessage(), OutputInterface::VERBOSITY_DEBUG);

                $invalidFiles[] = $file;
            }
        }

        if (!empty($validFiles)) {
            $output->writeln('Successfully converted files:');
            array_walk($validFiles, fn($file) => $output->writeln(Util::getFileLocalPath($file)));
        }

        if (!empty($invalidFiles)) {
            $output->writeln('Conversion failed for files:');
            array_walk($invalidFiles, fn($file) => $output->writeln(Util::getFileLocalPath($file)));
        }

        return self::SUCCESS;
    }
}