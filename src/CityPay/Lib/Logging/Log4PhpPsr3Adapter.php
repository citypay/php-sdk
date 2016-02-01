<?php
namespace CityPay\Lib\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use \Logger;
use \LoggerConfigurator;
use \LoggerLevel;

/**
 * Implements a default logger for the SDK.
 * 
 */
class Log4PhpPsr3Adapter
    extends AbstractLogger
    implements LoggerInterface
{
    use \CityPay\Lib\Logging\InterpolationTrait;
    
    private static $logLevelTranslation = array(
        LogLevel::EMERGENCY => LoggerLevel::FATAL,
        LogLevel::ALERT => LoggerLevel::FATAL,
        LogLevel::CRITICAL => LoggerLevel::FATAL,
        LogLevel::ERROR => LoggerLevel::ERROR,
        LogLevel::WARNING => LoggerLevel::WARN,
        LogLevel::NOTICE => LoggerLevel::INFO,
        LogLevel::INFO => LoggerLevel::INFO,
        LogLevel::DEBUG => LoggerLevel::DEBUG
    );
     
    /**
     *
     * @var Logger 
     */
    private $logger;
    
    /**
     * Constructor for the default logger which takes, as a parameter,
     * either -
     * 
     * (1)  an object implementing the LoggerConfigurator interface;
     * 
     * OR
     * 
     * (2)  a string representing the path name of a file containing the
     *      configuration for the construction of an object of type
     *      LoggerConfiguration.
     * 
     * @param string|array|LoggerConfigurator $
     * 
     */
    function __construct($logger = null) {
        $this->logger = $logger;
    }
    
    /**
     * 
     */
    function __destruct() {
        $this->logger = null;
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
                $this->logger->fatal($msg);
                break;
                
            case LogLevel::ALERT:
                $this->logger->fatal($msg);
                break;
                
            case LogLevel::CRITICAL:
                $this->logger->fatal($msg);
                break;
                
            case LogLevel::ERROR:
                $this->logger->error($msg);
                break;
                
            case LogLevel::WARNING:
                $this->logger->warn($msg);
                break;
            
            case LogLevel::NOTICE:
                $this->logger->info($msg);
                break;
            
            case LogLevel::INFO:
                $this->logger->info($msg);
                break;
            
            case LogLevel::DEBUG:
                $this->logger->debug($msg);
                break;
            
            default:
                throw new \Psr\Log\InvalidArgumentException();
        }
    }
}