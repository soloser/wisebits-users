<?php
declare(strict_types = 1);

namespace WiseUsers\Loggers;

interface LoggerInterface
{

    /**
     * @param LoggableInterface $loggable
     * @param string $message
     * @return string
     */
    public function log(LoggableInterface $loggable, string $message): string;
}