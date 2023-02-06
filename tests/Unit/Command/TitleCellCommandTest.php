<?php

namespace Tests\Unit\Command;

use MK\PhpofficeHelper\Command\TitleCellCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\PHPUnit\BaseTestCase;

final class TitleCellCommandTest extends BaseTestCase
{
    public function testExecuteSuccess()
    {
        $command = new TitleCellCommand();
        $command->setInputDir('/tests/Assets/Spreadsheets');
        $command->setOutputDir('/tests/tmp');

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}