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

    /**
     * @param int    $code
     * @param string $output
     * @param string $error
     */
    public function __construct($code, $output, $error)
    {
        $this->code = $code;
        $this->output = $output;
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
