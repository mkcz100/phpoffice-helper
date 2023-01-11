<?php
namespace MK\PhpofficeHelper\Enum;

use PhpOffice\PhpSpreadsheet\IOFactory;

enum WriterType: string {
    case XLSX = IOFactory::WRITER_XLSX;
    case PDF = 'Mpdf';

    public function extension(): string
    {
        return match ($this) {
            self::XLSX => 'xlsx',
            self::PDF => 'pdf'
        };
    }
}