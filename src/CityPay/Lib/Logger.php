<?php
namespace CityPay\Lib;

use Psr\Log\LoggerInterface;
use CityPay\Lib\Logging\Log4PhpPsr3Adapter;
use CityPay\Lib\Security\PciDssLogger;

/**
 * Logger, within the context of the CityPay\Lib package, is responsible
 * for providing an interface between the CityPay SDK for PHP and an
 * underlying logging platform.
 * 
 * Whereas the default logging platform for the CityPay SDK for PHP
 * is Apache log4PHP, it is expected that the SDK ought to support
 * substitution of the default logging platform with an logging platform
 * that implements PSR-3 to enable the SDK to link in to the logging
 * platform and configuration for the host application.
 * 
 * The difficulty with implementing this functionality at present lies
 * in -
 * 
 * (1)  the absence, under PSR-3, of a standardised interface for getting
 *      and setting a factory object for objects implementing the PSR-3
 *      LoggerInterface interface; and
 * 
 * (2)  the implementation of log4PHP which uses static data within
 *      the \Logger object for certain functionality thereby forcing
 *      the SDK to make calls to static functions with a resultant
 *      impact on any application-wide implementation of log4PHP.
 * 
 * To resolve (1), it is expected that the SDK will publish an interface
 * that host applications will implement for the purpose of supplying
 * appropriate, PSR-3 LoggerInterface adapted, logging targets.
 * 
 * To resolve (2), the SDK may publish an interface and two implementing
 * classes: a LoggerClass class, and a LoggerObject class to support
 * an application logger hierarchy that uses class references, and object
 * references for the root logger object, respectively.
 * 
 */
class Logger
{
    /**
     *
     * @var type 
     */
    private static $delegate = null;
    
    /**
     * 
     * @param type $configuration
     * @param type $configurator
     */
    public static function configure($configuration = null, $configurator = null) {
        $delegate = static::getLoggerDelegate();
        $delegate::configure($configuration, $configurator);
    }
    
    /**
     * Returns an array containing the default configuration of the default
     * logger implemented for the SDK.
     * 
     * The default configuration provides a logging message sink.
     * 
     * @return array
     */
    private static function getDefaultLoggerConfiguration() {
        return array(
            'rootLogger' => array(
                'appenders' => array('default')
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderNull'
                )
            )
        ); 
    }
    
    /**
     * 
     * @throws \IllegalArgumentException
     */
    private static function getLoggerDelegate($configuration = null) {
        $delegate = static::$delegate;
        if (!is_string($delegate)) {
            $delegate = '\Logger';
            if (is_null($configuration)) {
                //  Apply the default configurator.
                $delegate::configure(static::getDefaultLoggerConfiguration());
            } else if (is_object($configuration)
                && ($configuration instanceof LoggerConfigurator)) {
                    $delegate::configure($configuration);
            } else if (is_string($configuration)) {
                $delegate::configure($configuration);
            } else {
                throw new \IllegalArgumentException();
            }
            
            // static::setLoggerDelegate($delegate);
            
            static::$delegate = $delegate;
        }

        return $delegate;
    }
        
    /**
     * Returns a logger by name. 
     * 
     * @param string $name
     *      the name of the logger
     * 
     * @return LoggerInterface
     *      an object implementing LoggerInterface
     */
    public static function getLogger($name) {
        $delegate = static::getLoggerDelegate();
        $logger = $delegate::getLogger($name);
        return new PciDssLogger(
            new Log4PhpPsr3Adapter($logger)
        );
    }
}