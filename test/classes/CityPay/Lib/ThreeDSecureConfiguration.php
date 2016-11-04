<?php
namespace CityPay\Lib;

/**
 * 
 */
trait ThreeDSecureConfiguration
{
    /**
     *
     * @var type 
     */
    protected static $configuration;
    
    /**
     * 
     */
    protected static function initTrait() {
        
        echo dirname(dirname(dirname(dirname(__FILE__)))).'/etc/threedsecure.json';
        self::$configuration = new \CityPay\Lib\JsonConfiguration(
            dirname(dirname(dirname(dirname(__FILE__)))).'/etc/threedsecure.json'
        );
    }
    
    /**
     * 
     * @param type $identifier
     * @return type
     */
    private static function getPacket($identifier) {
        $v = self::$configuration->getOrElse($identifier, null);
        if (!is_null($v)) {
            return $v->getData();
        } else {
            return null;
        }
    }
    
    /**
     * 
     * @return type
     */
    protected static function getEncodedXmlPacket() {
        return self::getPacket("encodedXmlPacket");
    }
    
    /**
     * 
     * @return type
     */
    protected static function getRawXmlPacket() {
        return self::getPacket("rawXmlPacket");
    }
    
    /**
     * 
     * @return type
     */
    protected static function getPayPostRequestTestThreeDSSalePAReqPacket() {
        return self::getPacket("payPostRequestTestThreeDSSalePAReqPacket");
    }
    
    /**
     * 
     * @return type
     */
    protected static function getPayPostRequestTestThreeDSSalePAResPacket() {
        return self::getPacket("payPostRequestTestThreeDSSalePAResPacket");
    }
}