<?php

namespace MK\PhpofficeHelper\Model;

use SplFileInfo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetFileModel
{
    public function __construct(
        private Spreadsheet $spreadsheet,
        private SplFileInfo $file
    )
    {}

    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

}