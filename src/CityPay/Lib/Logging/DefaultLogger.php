<?php
namespace CityPay\Lib\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use \Logger;
use \LoggerLevel;

/**
 * Implements a default logger for the SDK.
 * 
 */
class DefaultLogger
    extends AbstractLogger
    implements LoggerInterface
{
    use \CityPay\Lib\Logging\Interpolation;
    
    static private $logLevelTranslation = array(
        LogLevel::EMERGENCY => LoggerLevel::FATAL,
        LogLevel::ALERT => LoggerLevel::FATAL,
        LogLevel::CRITICAL => LoggerLevel::FATAL,
        LogLevel::ERROR => LoggerLevel::ERROR,
        LogLevel::WARNING => LoggerLevel::WARN,
        LogLevel::NOTICE => LoggerLevel::INFO,
        LogLevel::INFO => LoggerLevel::INFO,
        LogLevel::DEBUG => LoggerLevel::DEBUG
    );
    
    private $log4php;
    
    /**
     * 
     * @param type $name
     */
    function __construct($name) {
        Logger::configure('config.xml');
        $this->log4php = Logger::getLogger($name);
    }
    
    /**
     * 
     */
    function __destruct() {
        $this->log4php = null;
    }
    
    /**
     * 
     * LoggerInterface implementation
     * 
     */
    public function log($level, $message, array $context = array()) {
        $msg = static::interpolate($message, $context);
        switch ($level) {
            case LogLevel::EMERGENCY:
                $this->log4php->fatal($msg);
                break;
                
            case LogLevel::ALERT:
                $this->log4php->fatal($msg);
                break;
                
            case LogLevel::CRITICAL:
                $this->log4php->fatal($msg);
                break;
                
            case LogLevel::ERROR:
                $this->log4php->error($msg);
                break;
                
            case LogLevel::WARNING:
                $this->log4php->warn($msg);
                break;
            
            case LogLevel::NOTICE:
                $this->log4php->info($msg);
                break;
            
            case LogLevel::INFO:
                $this->log4php->info($msg);
                break;
            
            case LogLevel::DEBUG:
                $this->log4php->debug($msg);
                break;
        }
    }
}