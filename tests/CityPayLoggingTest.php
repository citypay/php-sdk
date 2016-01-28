<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\Lib\Logger;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

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
    private static function getEchoLoggerConfig() {
        return array(
            'rootLogger' => array(
                'appenders' => array('default')
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderEcho',
                    'layout' => array(
                        'class' => 'LoggerLayoutSimple'
                    ),
                    'params' => array(
                        'htmlLineBreaks' => 'false'
                    )
                )
            )
        );
    }
    
    /**
     * 
     */
    public static function getFileLoggerConfig() {
        return array(
            'rootLogger' => array(
                'appenders' => array('default')
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderEcho',
                    'layout' => array(
                        'class' => 'LoggerLayoutSimple'
                    ),
                    'params' => array(
                        'htmlLineBreaks' => 'false'
                    )
                )
            )
        );
    }
    
    /**
     * 
     */
    public static function getLoggerConfig() {
        return static::getEchoLoggerConfig();
    }
    
    /**
     *
     */
    public function testCityPayDefaultLogger()
    {
        $logger = Logger::getLogger(__CLASS__);
        $this->assertTrue($logger instanceof LoggerInterface);
        
        $logger->emergency("EMERGENCY message");
        $logger->alert("ALERT message");
        $logger->critical("CRITICAL message");
        $logger->error("ERROR message");
        $logger->warning("WARNING message");
        $logger->notice("NOTICE message");
        $logger->info("INFO message");
        $logger->debug("DEBUG message");
        $logger->log(LogLevel::INFO, "INFO message, via log");
        
        $this->assertTrue(true);
    }
    
    public function testCityPayPciDssLogger()
    {
        $logger = Logger::getLogger(__CLASS__);
        Logger::configure(static::getEchoLoggerConfig());
        $this->assertTrue($logger instanceof LoggerInterface);
        
        $logger->emergency("EMERGENCY message");
        $logger->alert("ALERT message");
        $logger->critical("CRITICAL message");
        $logger->error("ERROR message");
        $logger->warning("WARNING message");
        $logger->notice("NOTICE message");
        $logger->info("INFO message");
        $logger->debug("DEBUG message");
        $logger->log(LogLevel::INFO, "INFO message, via log");
        
        $this->assertTrue(true);
        
        //$logger = 
    }
}