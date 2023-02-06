<?php

namespace Tests\PHPUnit;

use MK\PhpofficeHelper\Util;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class BaseTestCase extends TestCase
{
    protected function tearDown(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                Util::buildDir('/tests/tmp'), RecursiveDirectoryIterator::SKIP_DOTS
            ), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            /** @var \SplFileInfo $file */
            if ('.gitignore' === $file->getFilename()) {
                continue;
            }

            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
    }
}