<?php

/*
 * This file is part of CLI Executor.
 *
 * (c) Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Keradus\CliExecutor;

use Symfony\Component\Process\Process;

final class BashScriptExecutor
{
    /**
     * @var int
     */
    private static $tmpCounter = 0;

    /**
     * @var string[]
     */
    private $scriptParts;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var ?CliResult
     */
    private $result;

    /**
     * @var ?string
     */
    private $tmpFilePath;

    /**
     * @param string[] $scriptParts
     * @param string   $cwd
     */
    public function __construct($scriptParts, $cwd)
    {
        $this->scriptParts = $scriptParts;
        $this->cwd = $cwd;
    }

    public function __destruct()
    {
        if (null !== $this->tmpFilePath) {
            @unlink($this->tmpFilePath);
        }
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
        if (null === $this->result) {
            $tmpFileName = 'tmp-'.self::$tmpCounter++.'.sh';
            $tmpFileLines = array_merge(array('#!/usr/bin/env bash', 'set -e', ''), $this->scriptParts);
            $this->tmpFilePath = $this->cwd.'/'.$tmpFileName;
            file_put_contents($this->tmpFilePath, implode("\n", $tmpFileLines));
            chmod($this->tmpFilePath, 0777);
            $command = './'.$tmpFileName;

            $process = new Process($command, $this->cwd);
            $process->run();

            $this->result = new CliResult(
                $process->getExitCode(),
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        if (0 !== $this->result->getCode()) {
            throw new ExecutionException(
                $this->result,
                sprintf(
                    "Cannot execute `%s`:\n%s\nCode: %s\nExit text: %s\nError output: %s\nDetails:\n%s",
                    $command,
                    implode("\n", array_map(function ($line) { return "$ ${line}"; }, $tmpFileLines)),
                    $this->result->getCode(),
                    isset(Process::$exitCodes[$this->result->getCode()]) ? Process::$exitCodes[$this->result->getCode()] : 'Unknown exit code',
                    $this->result->getError(),
                    $this->result->getOutput()
                ),
                $process->getExitCode()
            );
        }

        return $this->result;
    }
}
