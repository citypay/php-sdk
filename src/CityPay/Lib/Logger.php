<?php
namespace CityPay\Lib;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use CityPay\Lib\Security\PciDssLogger;

/**
 * 
 */
class Logger
    implements LoggerAwareInterface
{
    private static $logger = null;
       
    /**
     * 
     * @return LoggerInterface
     *      an object implementing LoggerInterface.
     */
    public static function getLogger($name) {
        if (!(static::$logger instanceof LoggerInterface)) {
            static::$logger = new Logging\DefaultLogger($name);
        }
        return new PciDssLogger(static::$logger);
    }

    /**
     * 
     * LoggerAwareInterface implementation
     * 
     */
    
    /**
     * 
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger) {
        static::$logger = logger;
    }
}