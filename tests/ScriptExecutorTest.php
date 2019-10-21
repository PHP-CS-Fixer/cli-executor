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

use Keradus\CliExecutor\ScriptExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Keradus\CliExecutor\ScriptExecutor
 *
 * @internal
 */
class ScriptExecutorTest extends TestCase
{
    public function testSimpleExecution()
    {
        $scriptExecutor = ScriptExecutor::create(['ls'], __DIR__);

        $cliResult = $scriptExecutor->getResult();

        $this->assertContains(basename(__FILE__), $cliResult->getOutput());
    }
}
