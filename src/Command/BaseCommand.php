<?php

namespace MK\PhpofficeHelper\Command;

use ErrorException;
use Exception;
use SplFileInfo;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use MK\PhpofficeHelper\Util;
use MK\PhpofficeHelper\Model\SpreadsheetFileModel;
use MK\PhpofficeHelper\Enum\OutputType;
use MK\PhpofficeHelper\Enum\WriterType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{

    protected string $inputDir = '/input';
    protected string $outputDir = '/output';

    public function setInputDir(
        string $inputDir
    ): void
    {
        $this->inputDir = rtrim($inputDir, '/');
    }

    public function setOutputDir(
        string $outputDir
    ): void
    {
        $this->outputDir = rtrim($outputDir, '/');
    }

    abstract protected function processFile(SplFileInfo $file): Spreadsheet;

    protected function configure()
    {
        $this->setName('base-command');
        $this->setDescription('Base command class.');
        $this->addOption(
            'output',
            'o',
            InputOption::VALUE_OPTIONAL,
            'Output type: 1 for zip (default) or 2 for plain files in output folder',
            OutputType::ZIP->value
        );
    }

    /**
     * Override default writer type in subclasses
     * @see IOFactory::$writers
     *
     * @return WriterType
     */
    protected function getWriterType(): WriterType
    {
        return WriterType::XLSX;
    }

    /**
     * Executes command with following actions:
     * 1. Input validation
     * 2. Files processing implemented in inheriting class
     * 3. Files saving depending on OutputType enum
     * 4. Output messages and result
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        // 1. Input validation
        $type = (int) ($input->getOption('output') ?? OutputType::ZIP->value);
        if (null === ($outputType = OutputType::tryFrom($type))) {
            $output->writeln('Invalid output type, see command description to check available values');

            return self::FAILURE;
        }
        /**
         * Preparing some local variables
         * @var SplFileInfo[] $invalidFiles
         * @var SpreadsheetFileModel[] $spreadsheetFiles
         */
        $files = Util::loadDirectory(Util::buildDir($this->inputDir));
        $invalidFiles = [];
        $spreadsheetFiles = [];
        // 2. Files processing implemented in inheriting class
        foreach ($files as $file) {
            try {
                $spreadsheetFiles[] = new SpreadsheetFileModel($this->processFile($file), $file);
            } catch (Exception $e) {
                $output->writeln($e->getMessage(), OutputInterface::VERBOSITY_DEBUG);

                $invalidFiles[] = $file;
            }
        }
        // 3. Files saving depending on OutputType enum
        try {
            !empty($spreadsheetFiles) ? $this->saveFiles($spreadsheetFiles, $outputType) : null;
        } catch (Exception $e) {
            $output->writeln('Save process failed with error:');
            $output->writeln($e->getMessage(), OutputInterface::VERBOSITY_DEBUG);

            return self::FAILURE;
        }
        // 4. Output messages and result
        if (!empty($spreadsheetFiles)) {
            $output->writeln('Successfully converted files:');
            array_walk(
                $spreadsheetFiles,
                fn(SpreadsheetFileModel $spFile) => $output->writeln($spFile->getFile()->getFilename())
            );
        }

        if (!empty($invalidFiles)) {
            $output->writeln('Conversion failed for files:');
            array_walk(
                $invalidFiles,
                fn(SplFileInfo $file) => $output->writeln($spFile->getFile()->getFilename())
            );
        }

        return self::SUCCESS;
    }

    /**
     * Save files according to output type
     *
     * @see OutputType
     * @param SpreadsheetFileModel[] $spreadsheetFiles
     * @param OutputType $outputType
     * @return void
     */
    private function saveFiles(
        array $spreadsheetFiles,
        OutputType $outputType
    ): void
    {
        $folder = sprintf('/%s-%s-%s', (string) time(), $this->getName(), Util::randomString(8));
        $outputDir = Util::buildDir($this->outputDir . $folder);

        try {
            if (!mkdir($outputDir, 0755, true)) {
                throw new ErrorException(sprintf('Unable to create directory %s', $outputDir));
            }

            if (OutputType::ZIP === $outputType) {
                $zip = new ZipArchive();

                $zipPath = Util::buildDir($this->outputDir . $folder . '.zip');
                if (true !== $zip->open($zipPath, ZipArchive::CREATE)) {
                    throw new ErrorException('Unable to create zip archive');
                }
            }

            foreach ($spreadsheetFiles as $sfModel) {
                $writerType = $this->getWriterType();

                $outputFileName = str_ireplace(
                    '.xlsx',
                    '.' . $writerType->extension(),
                    $sfModel->getFile()->getFilename()
                );
                $fullSavePath = $outputDir . '/' . $outputFileName;

                $writer = IOFactory::createWriter($sfModel->getSpreadsheet(), $writerType->value);
                $writer->save($fullSavePath);

                if (OutputType::ZIP === $outputType) {
                    $zip->addFile($fullSavePath, $outputFileName);
                }
            }

            if (OutputType::ZIP === $outputType) {
                $zip->close();

                Util::deleteDir($outputDir);
            }
        } catch (Exception $e) {
            Util::deleteDir($outputDir);

            throw $e;
        }
    }
}