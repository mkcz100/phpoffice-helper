<?php
namespace MK\PhpofficeHelper\Enum;
enum OutputType: int {
    case ZIP = 1;
    case FOLDER = 2;

    public function name(): string
    {
        return match ($this) {
            self::ZIP => 'zip',
            self::FOLDER => 'folder'
        };
    }
}