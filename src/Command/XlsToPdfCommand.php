<?php

namespace MK\PhpofficeHelper\Command;

use SplFileInfo;
use MK\PhpofficeHelper\Enum\WriterType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class XlsToPdfCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('xls-to-pdf');
        $this->setDescription('Converts XLS files from input folder to PDF in output.');
    }

    protected function getWriterType(): WriterType
    {
        return WriterType::PDF;
    }

    protected function processFile(
        SplFileInfo $file
    ): Spreadsheet
    {
        return IOFactory::load($file->getPathname());
    }
}