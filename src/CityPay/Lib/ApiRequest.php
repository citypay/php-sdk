<?php
namespace CityPay\Lib;

use CityPay\Lib\Rpc\HttpsRpc;
use CityPay\Encoding\Serializable;
use CityPay\Encoding\Json\JsonSerializable;
use CityPay\Encoding\FormUrl\FormUrlSerializable;

use \CityPay\Lib\Security\PciDss;
use \CityPay\Lib\Security\PciDssLogger;
use \CityPay\Lib\Security\PciDssLoggable;

use Psr\Log\LoggerAwareInterface;

/**
 * 
 */
abstract class ApiRequest
    implements Serializable,
        FormUrlSerializable,
        JsonSerializable,
        LoggerAwareInterface,
        PciDssLoggable
{
    //
    //  Support logger wiring
    //
    use \Psr\Log\LoggerAwareTrait;
    
    //
    //
    //
    use \CityPay\Lib\PciDssLoggableNameValueComponent;
    
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
        
        $this->logger = \CityPay\Lib\Logger::getLogger(__CLASS__);
        self::initialiseNameValueComponent($apiConfig);
        self::set(
            "test",
            ($apiConfig::isApiTestingEnabled() ? "true" : "false")
        );
    }
    
    /**
     * 
     */
    protected function log(
        $level,
        $message,
        $context = array(),
        $elementTypeMap = array()
    ) {
        //
        //  Merge the PCI DSS loggable data with the $context array passed
        //  as a parameter, to generate the aggregate context for the log
        //  message.
        //
        $loggableElementMap = $this->getPciDssLoggableElementMap();
        $aggregateContext = array_merge($context, $loggableElementMap);
        
        //
        //  Merge the PCI DSS loggable metadata with the $elementTypeMap
        //  array passed as a parameter, to generate the aggregate element
        //  type map for the log message.
        //
        $loggableElementTypeMap = $this->getPciDssLoggableElementTypeMap();
        $aggregateElementTypeMap = array_merge($elementTypeMap, $loggableElementTypeMap);
       
        if (!is_null($this->logger) && $this->logger instanceof PciDssLogger) {
            $this->logger->log($level, $message, $aggregateContext, $aggregateElementTypeMap);
        }
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
        //  Log request
        //
        self::log(\Psr\Log\LogLevel::DEBUG,
            "ApiRequest::invokeRpcAndDeserializeResponse ["
                ."endpoint=\"{endpoint}\", "
                ."contentType=\"{contentType}\", "
                ."responseContentType=\"{responseContentType}\""
                ."]",
            array(
                'endpoint' => (($apiEndpoint != null) ? $apiEndpoint->getUrl() : 'null'),
                'contentType' => $contentType,
                'responseContentType' => $responseContentType
            )
        );
        
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
