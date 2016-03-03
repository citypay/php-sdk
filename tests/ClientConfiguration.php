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
     * @return \CityPay\Lib\Configuration\MerchantAccount
     */
    protected static function getElectronicCommerceCardholderAccountMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("CardHolderAccountMerchantAccount");
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceCardholderAccountMID() {
        return self::getElectronicCommerceCardholderAccountMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceCardholderAccountLicenceKey() {
        return self::getElectronicCommerceCardholderAccountMerchantAccount()->getLicenceKey();
    }
    
    /**
     * 
     * @return \CityPay\Lib\Configuration\MerchantAccount
     */
    protected static function getElectronicCommerceContinuousAuthorityMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("ContinuousAuthorityMerchantAccount");
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceContinuousAuthorityMID() {
        return self::getElectronicCommerceContinuousAuthorityMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceContinuousAuthorityLicenceKey() {
        return self::getElectronicCommerceContinuousAuthorityMerchantAccount()->getLicenceKey();
    }
    
    /**
     * 
     * @return \CityPay\Lib\Configuration\MerchantAccount
     */
    protected static function getElectronicCommerceHighPassMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("ElectronicCommerceHighPassMerchantAccount");
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceHighPassMID() {
        return self::getElectronicCommerceHighPassMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceHighPassLicenceKey() {
        return self::getElectronicCommerceHighPassMerchantAccount()->getLicenceKey();
    }
    
    /**
     * 
     * @return \CityPay\Lib\Configuration\MerchantAccount
     */
    protected static function getElectronicCommerceLowPassMerchantAccount() {
        return self::$clientConfiguration->getMerchantAccount("ElectronicCommerceLowPassMerchantAccount");
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceLowPassMID() {
        return self::getElectronicCommerceLowPassMerchantAccount()->getMID();
    }
    
    /**
     * 
     * @return string
     */
    protected static function getElectronicCommerceLowPassLicenceKey() {
        return self::getElectronicCommerceLowPassMerchantAccount()->getLicenceKey();
    }
}
