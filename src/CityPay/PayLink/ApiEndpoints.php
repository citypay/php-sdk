<?php
namespace CityPay\PayLink;

use CityPay\Lib\ApiEndpoint;

/**
 * 
 */
class ApiEndpoints {
       
    const DEFAULT_SALE_ENDPOINT_URL = "https://secure.citypay.com/paylink3/create";
    
    /**
     *
     * @var string
     */
    private static $saleEndpoint;

    /**
     * 
     */
    public static function sale() {
        if (!isset($saleEndpoint)) {
            $saleEndpoint = new ApiEndpoint(self::DEFAULT_SALE_ENDPOINT_URL);
        }
        
        return $saleEndpoint;
    }
}
