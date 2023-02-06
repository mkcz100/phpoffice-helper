<?php

namespace Tests\Unit\Command;

use MK\PhpofficeHelper\Command\XlsToPdfCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\PHPUnit\BaseTestCase;

final class XlsToPdfCommandTest extends BaseTestCase
{
    public function testExecuteSuccess()
    {
        $command = new XlsToPdfCommand();
        $command->setInputDir('/tests/Assets/Spreadsheets');
        $command->setOutputDir('/tests/tmp');

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}