<?php
namespace CityPay\PayLink;

use CityPay\Lib\ApiMessage;

/**
 * 
 */
class PayLinkApiError
    extends ApiMessage
{
    /**
     *
     * @var type 
     */
    private $responsePayload;
    
    /**
     * 
     * @param object $responsePayload
     */
    function __construct(
        $responsePayload
    ) {
        parent::__construct();
        $this->responsePayload = $responsePayload;
    }
    
    /**
     * 
     * @return array
     */
    public function __debugInfo() {
        if ($this->responsePayload == null) {
            return array();
        }
        
        return array(
            'id' => $this->responsePayload->id,
            'result' => $this->responsePayload->result,
            'errors' => $this->responsePayload->errors[0],
        );
    }
 
    /**
     * 
     * @return object
     */
    public function getResponseCode() {
        return $this->responsePayload;
    }
}