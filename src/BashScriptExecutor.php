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
     */
    public function __construct(array $scriptParts, string $cwd)
    {
        @trigger_error('`\Keradus\CliExecutor\BashScriptExecutor` is deprecated, use `\Keradus\CliExecutor\ScriptExecutor` instead.', E_USER_DEPRECATED);
        $this->scriptExecutor = new ScriptExecutor($scriptParts, $cwd, ['#!/usr/bin/env bash', 'set -e', '']);
    }

    /**
     * @param string[] $scriptParts
     */
    public static function create(array $scriptParts, string $cwd): self
    {
        return new self($scriptParts, $cwd);
    }

    /**
     * @throws ExecutionException
     */
    public function getResult(): CliResult
    {
        return $this->scriptExecutor->getResult();
    }
}
