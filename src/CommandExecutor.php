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

final class CommandExecutor
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var ?CliResult
     */
    private $result;

    /**
     * @param string $command
     * @param string $cwd
     */
    public function __construct($command, $cwd)
    {
        $this->command = $command;
        $this->cwd = $cwd;
    }

    /**
     * @param string $command
     * @param string $cwd
     *
     * @return self
     */
    public static function create($command, $cwd)
    {
        return new self($command, $cwd);
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
            // for symfony/process:^4.2
            if (method_exists(Process::class, 'fromShellCommandline')) {
                $process = Process::fromShellCommandline($this->command, $this->cwd);
            } else {
                $process = new Process($this->command, $this->cwd);
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
                    "Cannot execute `%s`:\nCode: %s\nExit text: %s\nError output: %s\nDetails:\n%s",
                    $this->command,
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
