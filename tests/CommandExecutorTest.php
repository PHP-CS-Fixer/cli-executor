<?php

declare(strict_types=1);

/*
 * This file is part of CLI Executor.
 *
 * (c) Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Keradus\Tests;

use Keradus\CliExecutor\CommandExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Keradus\CliExecutor\CommandExecutor
 *
 * @internal
 */
final class CommandExecutorTest extends TestCase
{
    public function testSimpleExecution(): void
    {
        $scriptExecutor = CommandExecutor::create('ls -l', __DIR__);

        $cliResult = $scriptExecutor->getResult();

        if (\is_callable([$this, 'assertStringContainsString'])) {
            self::assertStringContainsString(basename(__FILE__), $cliResult->getOutput());
        } else {
            self::assertContains(basename(__FILE__), $cliResult->getOutput());
        }
    }

    public function testSimpleExecutionWithArray(): void
    {
        $scriptExecutor = CommandExecutor::create(['ls', '-l'], __DIR__);

        $cliResult = $scriptExecutor->getResult();

        if (\is_callable([$this, 'assertStringContainsString'])) {
            self::assertStringContainsString(basename(__FILE__), $cliResult->getOutput());
        } else {
            self::assertContains(basename(__FILE__), $cliResult->getOutput());
        }
    }
}
