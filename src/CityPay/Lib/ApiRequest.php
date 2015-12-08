<?php
namespace CityPay\Lib;

use CityPay\Lib\Rpc\HttpsRpc;
use CityPay\Encoding\Json\JsonSerializable;
use CityPay\Encoding\FormUrl\FormUrlCodec;
use CityPay\Encoding\Json\JsonCodec;
use CityPay\Encoding\Xml\XmlCodec;

/**
 * 
 */
abstract class ApiRequest
    implements JsonSerializable
{
    use \CityPay\Lib\NameValueComponent;
    
    /**
     * 
     */
    function __construct() {
        
    }
    
    /**
     * 
     */
    protected abstract function this();
    
    /**
     * 
     * @param type $apiEndpoint
     * @param type $contentType
     * @param type $payload
     * @param type $responseContentType
     * @param type $responsePayload
     * @return type
     */
    /*protected function invokeRpc(
        $apiEndpoint,
        $contentType,
        $payload,
        $responseContentType,
        &$responsePayload
    ) {
        //
        //  Create responsePayload variable to pass by reference to
        //  HttpsRpc::invoke.
        //
        $responsePayload = null;
        
        //
        //
        //
        $responseCode = HttpsRpc::invoke(
            $apiEndpoint->getUrl(),
            $contentType,
            $this,
            $responseContentType,
            $responsePayload
        );
            
        return $responseCode;
    }*/
    
    /**
     * @deprecated
     * @param type $apiEndpoint
     * @param type $contentType
     * @param type $payload
     * @param type $responseContentType
     * @param type $responsePayload
     * @return type
     */
    protected function invokeRpcAndDeserializeResponse(
        $apiEndpoint,
        $contentType,
        $responseContentType,
        &$deserializedPayload
    ) {      
        //
        //  Create responsePayload variable to pass by reference to
        //  HttpsRpc::invoke.
        //
        $responsePayload = null;
        
        //
        //
        //
        $responseCode = HttpsRpc::invoke(
            $apiEndpoint->getUrl(),
            $contentType,
            $this->mapNameValue,
            $responseContentType,
            $deserializedPayload
        );
           
        return $responseCode;
    }
    
    /**
     * 
     * @return type
     */
    public function jsonSerialize() {
        return $this->mapNameValue;
    }
}
