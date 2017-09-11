<?php
namespace CityPay\PayLink;

use CityPay\Lib\ApiMessage;

class TransportError extends ApiMessage {

    private $responseCode;

    public function getResponseCode() {
        return $this->responseCode;
    }

    /**
     * 
     * @param string $responseCode
     */
    function __construct($responseCode) {
        parent::__construct();
        $this->responseCode = $responseCode;
    }
}