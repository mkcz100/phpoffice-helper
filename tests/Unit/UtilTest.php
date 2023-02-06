<?php

namespace Tests\Unit;

use MK\PhpofficeHelper\Util;
use PHPUnit\Framework\TestCase;

final class UtilTest extends TestCase
{
    private ?string $tmpDir = null;

    public function testBuildDir(): void
    {
        $dir = Util::buildDir('/output');

        $this->assertSame(dirname(__DIR__, 2) . '/output', $dir);
    }

    public function testLoadDirectory(): void
    {
        $testFiles = ['test1.xml', 'test2.json', 'test3.php'];
        $this->tmpDir = sys_get_temp_dir() . '/' . 'dir_' . uniqid();

        if (!mkdir($this->tmpDir)) {
            $this->fail(sprintf('Unable to create %s directory', $this->tmpDir));
        }

        array_walk(
            $testFiles,
            fn($name) => file_put_contents($this->tmpDir . '/' . $name, '')
        );

        $splFiles = Util::loadDirectory($this->tmpDir);

        $this->assertSame(count($testFiles), count($splFiles));

        array_walk(
            $splFiles,
            fn($splFile) => $this->assertTrue(
                array_search($splFile->getFilename(), $testFiles) !== false
            )
        );
    }

    public function testRandomString(): void
    {
        $counts = [5, 28, 15, 33, 1, 0, -2];

        array_walk(
            $counts,
            fn($count) =>
            $this->assertSame($count < 0 ? 0 : $count, strlen(Util::randomString($count)))
        );
    }

    public function testDeleteDir(): void
    {
        $dir = sys_get_temp_dir() . '/' . 'test-remove-' . uniqid();

        if (!mkdir($dir)) {
            $this->fail(sprintf('Unable to create %s directory', $dir));
        }

        $this->assertTrue(Util::deleteDir($dir));
        $this->assertFalse(file_exists($dir));
    }

    /**
     * Run after each test
     * @return void
     */
    protected function tearDown(): void
    {
        if (null !== $this->tmpDir && file_exists($this->tmpDir)) {
            shell_exec("rm -rf " . $this->tmpDir);
            $this->tmpDir = null;
        }
    }
}