<?php

namespace MK\PhpofficeHelper;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Util
{
    public static function buildDir(
        string $path = ''
    ): string
    {
        return dirname(__DIR__, 1) . $path;
    }
    /**
     * @return SplFileInfo[]
     */
    public static function loadDirectory(
        string $dir
    ): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $resultFiles = [];

        foreach ($rii as $file) {
            /** @var SplFileInfo $file */
            if (!$file->isFile()) {
                continue;
            }

            if ('.gitignore' === $file->getFilename()) {
                continue;
            }

            $resultFiles[] = $file->getFileInfo();
        }

        return $resultFiles;
    }
}