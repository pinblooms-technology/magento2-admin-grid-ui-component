<?php

namespace PinBlooms\MasterData\Logger;

use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class Logger extends MonologLogger implements LoggerInterface
{
    /**
     * Logger constructor.
     *
     * @param string $name
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(
        string $name,
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
    }
}
