<?php

namespace CityPay\PayLink;

use CityPay\Lib\Rpc\Http;
use CityPay\Lib\Rpc\HttpsRpc;
use CityPay\Lib\ApiRequest;

use CityPay\Lib\Security\PciDss;

/**
 *
 */
class PayLinkRequest
    extends ApiRequest
{
    /**
     *
     */
    function __construct(
        $apiConfig = \CityPay\Config\DefaultConfig::class
    )
    {
        parent::__construct($apiConfig);
    }

    /**
     * @param $address
     * @return $this
     */
    public function address(
        $address
    )
    {
        parent::set("address", $address);
        return $this->this();
    }

    /**
     * @param $amount
     * @return $this
     */
    public function amount(
        $amount
    )
    {
        parent::set("amount", $amount);
        return $this->this();
    }

    /**
     *
     * @param \CityPay\PayLink\Cardholder $cardholder
     * @return \CityPay\PayLink\PayLinkRequest
     */
    public function cardholder(
        $cardholder
    )
    {
        parent::set("cardholder", $cardholder);
        return $this->this();
    }

    /**
     * @param \CityPay\PayLink\Configuration $configuration
     * @return \CityPay\PayLink\PayLinkRequest
     */
    public function configuration(
        $configuration
    )
    {
        parent::set("config", $configuration);
        return $this->this();
    }

    /**
     * @param $currencyCode
     * @return $this
     */
    public function currency(
        $currencyCode
    )
    {
        parent::set("currency", $currencyCode);
        return $this->this();
    }

    /**
     * @param $identifier
     * @return $this
     */
    public function identifier(
        $identifier
    )
    {
        parent::set("identifier", $identifier);
        return $this->this();
    }

    /**
     * @param $merchantId
     * @return $this
     */
    public function merchantId(
        $merchantId
    )
    {
        parent::set("merchantId", $merchantId);
        return $this->this();
    }

    /**
     * @param $licenceKey
     * @return $this
     */
    public function licenceKey(
        $licenceKey
    )
    {
        parent::set("licenceKey", $licenceKey);
        return $this->this();
    }

    /**
     * @param $test
     * @return $this
     */
    public function test(
        $test
    )
    {
        parent::set("test", $test);
        return $this->this();
    }

    /**
     *
     * @return \CityPay\PayLink\PayLinkRequest
     */
    protected function this()
    {
        return $this;
    }

    /**
     * @param $transInfo
     * @return $this
     */
    public function transInfo(
        $transInfo
    )
    {
        parent::set("trans_info", $transInfo);
        return $this->this();
    }

    /**
     * @param $transType
     * @return $this
     */
    public function transType(
        $transType
    )
    {
        parent::set("trans_type", $transType);
        return $this->this();
    }

    public function createToken()
    {
        $responsePayload = null;

        $responseCode = self::invokeRpcAndDeserializeResponse(
            ApiEndpoints::sale(),
            HttpsRpc::CONTENT_TYPE_JSON,
            HttpsRpc::CONTENT_TYPE_JSON,
            $responsePayload
        );

        if ($responseCode == Http::HTTP_OK && $responsePayload != null) {
            $result = $responsePayload->result;
            $id = $responsePayload->id;

            if ($result == 0x01) {
                $url = $responsePayload->url;
                return new PayLinkToken($id, $url);
            } else {
                return new PayLinkApiError($responsePayload);
            }
        } else {
            return new TransportError($responseCode, $responsePayload);
        }
    }

    /**
     * @deprecated see createToken
     */
    public function saleTransaction()
    {
        return $this->createToken();
    }

    /**
     *
     *
     */
    protected function getPciDssLoggableSensitiveElementTypeMap()
    {
        return array(
            'merchantId' => PciDss::MERCHANTID,
            'licenceKey' => PciDss::LICENCEKEY,
        );
    }
}