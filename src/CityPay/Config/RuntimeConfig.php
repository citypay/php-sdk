<?php
namespace CityPay\Config;

/**
 * The RuntimeConfig abstract class provides a baseline implementation of
 * some static functions that are used within the SDK to determine default
 * settings for -
 * 
 * (1)  whether transactions are processed as "test" transactions; and
 * 
 * (2)  the appropriate logging level for the application.
 * 
 */
abstract class RuntimeConfig
{   
    /**
     * 
     */
    public static function isApiTestingEnabled() {
        throw new \CityPay\Lib\UnsupportedOperationException();
    }
    
    /**
     * 
     */
    public static function getApiLoggingLevel() {
        throw new \CityPay\Lib\UnsupportedOperationException();
    }
}