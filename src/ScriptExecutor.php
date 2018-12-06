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

final class ScriptExecutor
{
    /**
     * @var int
     */
    private static $tmpCounter = 0;

    /**
     * @var string[]
     */
    private $scriptInit;

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
     * @param string[]  $scriptParts
     * @param string    $cwd
     * @param ?string[] $scriptInit
     */
    public function __construct($scriptParts, $cwd, array $scriptInit = null)
    {
        $this->scriptParts = $scriptParts;
        $this->cwd = $cwd;
        $this->scriptInit = null !== $scriptInit ? $scriptInit : array('#!/bin/sh', 'set -eu', '');
    }

    public function __destruct()
    {
        if (null !== $this->tmpFilePath) {
            @unlink($this->tmpFilePath);
        }
    }

    /**
     * @param string[]  $scriptParts
     * @param string    $cwd
     * @param ?string[] $scriptInit
     *
     * @return self
     */
    public static function create($scriptParts, $cwd, array $scriptInit = null)
    {
        return new self($scriptParts, $cwd, $scriptInit);
    }

    /**
     * @param bool $checkCode
     *
     * @throws ExecutionException
     *
     * @return CliResult
     */
    public function getResult($checkCode = true)
    {
        if (null === $this->result) {
            $tmpFileName = 'tmp-'.self::$tmpCounter++.'.sh';
            $tmpFileLines = array_merge($this->scriptInit, $this->scriptParts);
            $this->tmpFilePath = $this->cwd.'/'.$tmpFileName;
            file_put_contents($this->tmpFilePath, implode("\n", $tmpFileLines));
            chmod($this->tmpFilePath, 0777);
            $command = './'.$tmpFileName;

            if (method_exists(Process::class, 'fromShellCommandline')) {
                $process = Process::fromShellCommandline($command, $this->cwd);
            } else {
                $process = new Process($command, $this->cwd);
            }
            $process->run();

            $this->result = new CliResult(
                $process->getExitCode(),
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        if ($checkCode && 0 !== $this->result->getCode()) {
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
