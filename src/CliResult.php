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

final class CliResult
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $error;

    public function __construct(int $code, string $output, string $error)
    {
        $this->code = $code;
        $this->output = $output;
        $this->error = $error;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
