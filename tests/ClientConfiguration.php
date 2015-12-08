<?php
/**
 * 
 */
trait ClientConfiguration
{
    /**
     *
     * @var type 
     */
    protected static $clientConfiguration;
    
    /**
     * 
     */
    public static function setUpBeforeClass()
    {
        $x = dirname(dirname(__FILE__));
        $y = $x.'/resources/configuration.json';
        self::$clientConfiguration = new \CityPay\Lib\Configuration(
            dirname(dirname(__FILE__)).'/resources/configuration.json'
        );
    }
    
    /**
     * 
     * @return type
     */
    protected static function getClientLicenceKey() {
        return self::$clientConfiguration->getClientLicenceKey();
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceHighPassMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("ElectronicCommerceHighPassMerchantAccount");
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceHighPassMID() {
        return self::getElectronicCommerceHighPassMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceHighPassLicenceKey() {
        return self::getElectronicCommerceHighPassMerchantAccount()->getLicenceKey();
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceLowPassMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("ElectronicCommerceLowPassMerchantAccount");
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceLowPassMID() {
        return self::getElectronicCommerceLowPassMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return type
     */
    protected static function getElectronicCommerceLowPassLicenceKey() {
        return self::getElectronicCommerceLowPassMerchantAccount()->getLicenceKey();
    }
}
