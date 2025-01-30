<?php

namespace PinBlooms\MasterData\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level Variable
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name Variable
     *
     * @var string
     */
    protected $fileName = '/var/log/pinblooms_masterdata.log';
}
