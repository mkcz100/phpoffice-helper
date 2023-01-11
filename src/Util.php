<?php

namespace MK\PhpofficeHelper;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Helper class which containts simple static functions for general use in project
 */
class Util
{
    public static function buildDir(
        string $path = ''
    ): string
    {
        return dirname(__DIR__, 1) . $path;
    }

    /**
     * Get files from directory and return as array
     *
     * @param string $dir
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

    public static function randomString(
        int $length = 8
    ): string
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil($length / strlen($x))
                )
            ),
            1,
            $length
        );
    }

    /**
     * Delete directory with all files in it
     *
     * @todo Might be wise to replace it by symfony/filesystem in the future
     *
     * @param string $rootPath
     * @return bool
     */
    public static function deleteDir(
        string $rootPath
    ): bool
    {
        if (!file_exists($rootPath)) {
            return true;
        }

        foreach (new DirectoryIterator($rootPath) as $fileToDelete) {
            if ($fileToDelete->isDot()) {
                continue;
            }

            if ($fileToDelete->isFile()) {
                unlink($fileToDelete->getPathName());
            }

            if ($fileToDelete->isDir()) {
                self::deleteDir($fileToDelete->getPathName());
            }
        }

        return rmdir($rootPath);
    }
}