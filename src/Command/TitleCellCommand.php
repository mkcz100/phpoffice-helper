<?php

namespace MK\PhpofficeHelper\Command;

use MK\PhpofficeHelper\Util;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SplFileInfo;

class TitleCellCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('title-cell');
        $this->setDescription('Adds title cell in every worksheet and exports new XLS file to output folder.');
    }
    protected function processFile(
        SplFileInfo $file
    ): void
    {
        $spreadsheet = IOFactory::load($file->getPathname());

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $highestColumn = $sheet->getHighestDataColumn(1);

            // merge cells at first row
            $sheet->insertNewRowBefore(1);
            $sheet->mergeCells('A1:' . $highestColumn . '1');

            $sheet->getCell('A1')->setValue($sheet->getTitle());

            $sheet->getCell('A1')->getStyle()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getCell('A1')->getStyle()
                ->getFont()
                ->setBold(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLSX);
        $writer->save($this->outputDir . Util::getFileLocalPath($file));
    }
}