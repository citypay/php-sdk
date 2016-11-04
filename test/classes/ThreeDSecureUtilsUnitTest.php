<?php
require(__DIR__.'/../../vendor/autoload.php');

use \CityPay\Lib\ThreeDSecureUtils;

class ThreeDSecureUtilsUnitTest
    extends PHPUnit_Framework_TestCase 
{
    use \CityPay\Lib\ClientConfiguration,
        \CityPay\Lib\ThreeDSecureConfiguration {
        \CityPay\Lib\ClientConfiguration::initTrait insteadof \CityPay\Lib\ThreeDSecureConfiguration;
        \CityPay\Lib\ClientConfiguration::initTrait as initClientConfigurationTrait;
        \CityPay\Lib\ThreeDSecureConfiguration::initTrait as initThreeDSecureConfigurationTrait;
    }
    
    /**
     * 
     */
    public static function setUpBeforeClass() {
        self::initClientConfigurationTrait();
        self::initThreeDSecureConfigurationTrait();
    }
    
    /**
     * 
     */
    function test()
    {
        $b64_in = ThreeDSecureUtils::encode(self::getRawXmlPacket());
        echo $b64_in;
        echo "\r\n";
        echo "\r\n";

        $b64_in_clean = preg_replace("[\\r\\n]", '', $b64_in);
        echo $b64_in_clean;
        echo "\r\n";
        echo "\r\n";
        
        $b64_out = ThreeDSecureUtils::decode(self::getEncodedXmlPacket());
        echo $b64_out;
        echo "\r\n";
        echo "\r\n";
    }
}