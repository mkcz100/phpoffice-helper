<?php

namespace MK\PhpofficeHelper\Command;

use MK\PhpofficeHelper\Util;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class XlsToPdfCommand extends BaseCommand
{
    protected const PDF_WRITER_TYPE = 'Mpdf';

    protected function configure()
    {
        parent::configure();
        $this->setName('xls-to-pdf');
        $this->setDescription('Converts XLS files from input folder to PDF in output.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $inputDir = Util::buildDir('/input');
        $outputDir = Util::buildDir('/output');

        $files = Util::loadDirectory($inputDir);

        $validFiles = [];
        $invalidFiles = [];

        foreach ($files as $file) {
            $xlsRelativePath = str_replace($file->getPath(), '', $file->getPathname());
            $pdfRelativePath = str_replace($file->getExtension(), 'pdf', $xlsRelativePath);

            try {
                $spreadsheet = IOFactory::load($file->getPathname());

                $writer = IOFactory::createWriter($spreadsheet, self::PDF_WRITER_TYPE);
                $writer->save($outputDir . $pdfRelativePath);

                $validFiles[] = $xlsRelativePath;
            } catch (Exception $e) {
                $output->writeln($e->getMessage(), OutputInterface::VERBOSITY_DEBUG);
                $invalidFiles[] = $xlsRelativePath;
            }
        }

        if(!empty($validFiles)) {
            $output->writeln('Successfully converted files:');
            array_walk($validFiles, fn($file) => $output->writeln($file));
        }

        if(!empty($invalidFiles)) {
            $output->writeln('Conversion failed for files:');
            array_walk($invalidFiles, fn($file) => $output->writeln($file));
        }

        return self::SUCCESS;
    }
}