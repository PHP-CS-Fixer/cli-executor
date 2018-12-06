<?php

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
class CommandExecutorTest extends TestCase
{
    public function testSimpleExecution()
    {
        $scriptExecutor = CommandExecutor::create('ls -l', __DIR__);

        $cliResult = $scriptExecutor->getResult();

        $this->assertContains(basename(__FILE__), $cliResult->getOutput());
    }
}
