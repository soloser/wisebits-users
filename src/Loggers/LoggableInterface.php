<?php
declare(strict_types = 1);

namespace WiseUsers\Loggers;

interface LoggableInterface
{

    /**
     * @return string
     */
    public function toString(): string;
}