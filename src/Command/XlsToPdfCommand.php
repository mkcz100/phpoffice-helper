<?php

namespace MK\PhpofficeHelper\Command;

use MK\PhpofficeHelper\Util;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SplFileInfo;

class XlsToPdfCommand extends BaseCommand
{
    protected const PDF_WRITER_TYPE = 'Mpdf';

    protected function configure()
    {
        parent::configure();
        $this->setName('xls-to-pdf');
        $this->setDescription('Converts XLS files from input folder to PDF in output.');
    }

    protected function processFile(
        SplFileInfo $file
    ): void
    {
        $xlsRelativePath = Util::getFileLocalPath($file);
        $pdfRelativePath = str_replace('.' . $file->getExtension(), '.pdf', $xlsRelativePath);

        $spreadsheet = IOFactory::load($file->getPathname());

        $writer = IOFactory::createWriter($spreadsheet, self::PDF_WRITER_TYPE);
        $writer->save($this->outputDir . $pdfRelativePath);
    }
}