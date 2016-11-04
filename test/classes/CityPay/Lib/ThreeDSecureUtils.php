<?php
namespace CityPay\Lib;

class ThreeDSecureUtils
{
    /**
     * 
     */
    static function encode($data)
    {
        $compressedData = zlib_encode($data, ZLIB_ENCODING_DEFLATE);
        if ($compressedData) {
            return base64_encode($compressedData);
        }
        
        return false;
    }

    /**
     * $data    Base64 encoded string
     * 
     * 
     */
    static function decode($encodedData)
    {
        $compressedData = base64_decode($encodedData, true);    
        if ($compressedData != false) {
            return zlib_decode($compressedData);
        }
        
        return false;
    }
}