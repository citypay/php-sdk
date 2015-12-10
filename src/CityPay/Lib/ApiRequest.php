<?php
namespace CityPay\Lib;

use CityPay\Lib\Rpc\HttpsRpc;
use CityPay\Encoding\Serializable;
use CityPay\Encoding\Json\JsonSerializable;
use CityPay\Encoding\FormUrl\FormUrlSerializable;

/**
 * 
 */
abstract class ApiRequest
    implements Serializable,
        FormUrlSerializable,
        JsonSerializable
{
    use \CityPay\Lib\NameValueComponent;
    
    /**
     * 
     * @param type $apiConfig
     */
    function __construct(
        $apiConfig = \CityPay\Config\DefaultConfig::class
    ) {
        if (!is_subclass_of($apiConfig, \CityPay\Config\RuntimeConfig::class)) {
            throw new \InvalidArgumentException();
        }
        
        self::initialiseNameValueComponent($apiConfig);
        self::set(
            "test",
            ($apiConfig::isApiTestingEnabled() ? "true" : "false")
        );
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
            $this,
            $responseContentType,
            $deserializedPayload
        );
           
        return $responseCode;
    }
    
    /**
     * @return array
     */
    public function formUrlSerialize() {
        return $this->mapNameValue;
    }
    
    /**
     * 
     * @return array
     */
    public function jsonSerialize() {
        return $this->mapNameValue;
    }
}
