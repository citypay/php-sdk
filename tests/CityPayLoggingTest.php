<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\Lib\Logger;
use Psr\Log\LogLevel;

/**
 * 
 */
class CityPayLoggingTest
    extends PHPUnit_Framework_TestCase
{
    use ClientConfiguration;
    
    /**
     *
     */
    public function testCityPayDefaultLogger()
    {
        $logger = Logger::getLogger(__CLASS__);
        $logger->emergency("EMERGENCY message");
        $logger->alert("ALERT message");
        $logger->critical("CRITICAL message");
        $logger->error("ERROR message");
        $logger->warning("WARNING message");
        $logger->notice("NOTICE message");
        $logger->info("INFO message");
        $logger->debug("DEBUG message");
        $logger->log(LogLevel::INFO, "INFO message, via log");
    }
}