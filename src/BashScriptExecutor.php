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

namespace Keradus\CliExecutor;

/**
 * @deprecated, use `\Keradus\CliExecutor\ScriptExecutor` instead
 */
final class BashScriptExecutor
{
    /**
     * @var ScriptExecutor
     */
    private $scriptExecutor;

    /**
     * @param string[] $scriptParts
     * @param string   $cwd
     */
    public function __construct($scriptParts, $cwd)
    {
        @trigger_error('`\Keradus\CliExecutor\BashScriptExecutor` is deprecated, use `\Keradus\CliExecutor\ScriptExecutor` instead.', E_USER_DEPRECATED);
        $this->scriptExecutor = new ScriptExecutor($scriptParts, $cwd, ['#!/usr/bin/env bash', 'set -e', '']);
    }

    /**
     * @param string[] $scriptParts
     * @param string   $cwd
     *
     * @return self
     */
    public static function create($scriptParts, $cwd)
    {
        return new self($scriptParts, $cwd);
    }

    /**
     * @throws ExecutionException
     *
     * @return CliResult
     */
    public function getResult()
    {
        return $this->scriptExecutor->getResult();
    }
}
