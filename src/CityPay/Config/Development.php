<?php
namespace CityPay\Config;

/**
 * The Development class overrides the baseline RuntimeConfig implementation 
 * of some certain static functions that are used within the SDK to determine
 * default settings for -
 * 
 * (1)  whether transactions are processed as "test" transactions; and
 * 
 * (2)  the appropriate logging level for the application.
 * 
 */
class Development
    extends RuntimeConfig
{
    /**
     * 
     */
    public static function isApiTestingEnabled() {
        return true;
    }
    
    /**
     * 
     */
    public static function getApiLoggingLevel() {
        throw new \CityPay\Lib\UnsupportedOperationException();
    }
}
    

