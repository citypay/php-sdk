<?php
namespace CityPay\PayPost;

use CityPay\Lib\ApiEndpoint;

/**
 * 
 */
class ApiEndpoints {
    //
    //
    //
    const DEFAULT_SALE_ENDPOINT_URL = "https://secure.citypay.com/paypost/api";
    
    //
    //
    //
    const DEFAULT_NOTIFY_ACS_RESULT_ENDPOINT_URL = "https://secure.citypay.com/paypost/api";
    
    //
    //
    //
    const DEFAULT_REFUND_ENDPOINT_URL = "https://";
    
    //
    //
    //
    const DEFAULT_CANCEL_ENDPOINT_URL = "https://secure.citypay.com/ecom/Cancel";
    
    //
    //
    //
    const DEFAULT_COMPLETE_ENDPOINT_URL = "https://secure.citypay.com/ecom/Complete";
    
    //
    //
    //
    private static $saleEndpoint;
    
    //
    //
    //
    private static $notifyAcsResultEndpoint;
    
    //
    //
    //
    private static $refundEndpoint;
    
    //
    //
    //
    private static $cancelEndpoint;
    
    //
    //
    //
    private static $completeEndpoint;

    /**
     * 
     */
    public static function sale() {
        if (!isset(self::$saleEndpoint)) {
            self::$saleEndpoint
                = new ApiEndpoint(self::DEFAULT_SALE_ENDPOINT_URL);
        }
        
        return self::$saleEndpoint;
    }
    
    /**
     * 
     */
    public static function notifyAcsResult() {
        if (!isset(self::$notifyAcsResultEndpoint)) {
            self::$notifyAcsResultEndpoint
                = new ApiEndpoint(self::DEFAULT_NOTIFY_ACS_RESULT_ENDPOINT_URL);
        }
        
        return self::$notifyAcsResultEndpoint;
    }
    
    /**
     * 
     */
    public static function refund() {
        if (!isset(self::$refundEndpoint)) {
            self::$refundEndpoint
                = new ApiEndpoint(self::DEFAULT_REFUND_ENDPOINT_URL);
        }
        
        return self::$refundEndpoint;
    }
    
    /**
     * 
     */
    public static function cancel() {
        if (!isset(self::$cancelEndpoint)) {
            self::$cancelEndpoint
                = new ApiEndpoint(self::DEFAULT_CANCEL_ENDPOINT_URL);
        }
        
        return self::$cancelEndpoint;
    }
    
    /**
     * 
     */
    public static function complete() {
        if (!isset(self::$completeEndpoint)) {
            self::$completeEndpoint
                = new ApiEndpoint(self::DEFAULT_COMPLETE_ENDPOINT_URL);
        }
        
        return self::$completeEndpoint;
    }
}



