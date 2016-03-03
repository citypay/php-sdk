<?php
namespace CityPay\PayPost;

use CityPay\Lib\ApiEncoding;
use CityPay\Lib\Rpc\Http;
use CityPay\Lib\NamedValueNotFoundException;
use CityPay\Lib\InvalidGatewayResponseException;

class AcsPayPostRequest
    extends PayPostRequest
{
    /**
     * 
     * @param type $apiConfig
     */
    function __construct(
        $apiConfig = \CityPay\Config\DefaultConfig::class
    ) {
        parent::__construct($apiConfig);
    }
    
    /**
     * 
     * @return \CityPay\PayPost\AcsPayPostRequest
     */
    protected function this() {
        return $this;
    }
    
    /**
     * This URL must exist on your website and which will forward the response
     * (an encoded PaRes) from the 3D-Secure authorisation process (ACS) to our
     * server for subsequent authorisation. The URL is provided at the point of
     * request in order to construct the encoded data to the ACS (PaReq) which
     * if authentication is required, will be returned to you within the
     * response.
     *
     * REQUIRED for 3D-Secure Processing
     *
     * @param merchantTermURL
     * @return \CityPay\PayPost\AcsPayPostRequest
     */
    public function merchantTermURL(
        $merchantTermURL
    ) {
        self::set("merchant_termurl", $merchantTermURL);
        return $this->this();
    }

    /**
     * The exact content of the HTTP user-agent header as sent to the merchant
     * from the cardholder's user agent. This value will be validated by the
     * ACS when the card holder authenticates themselves to verify that no
     * intermediary is performing this action.
     *
     * REQUIRED for 3D-Secure Processing
     *
     * @param userAgent
     * @return \CityPay\PayPost\AcsPayPostRequest
     */
    public function userAgent(
        $userAgent
    ) {
        self::set("user_agent", $userAgent);
        return $this->this();
    }

    /**
     * The exact content of the HTTP accept header as sent to the merchant from
     * the cardholder's user agent. This value will be validated by the ACS
     * when the card holder authenticates themselves to verify that no
     * intermediary is performing this action.
     *
     * REQUIRED for 3D-Secure Processing
     *
     * @param acceptHeaders
     * @return \CityPay\PayPost\AcsPayPostRequest
     */
    public function acceptHeaders(
        $acceptHeaders
    ) {
        self::set("accept_headers", $acceptHeaders);
        return $this->this();
    }
    
    /**
     * 
     * @return \CityPay\Lib\ApiMessage
     * @throws InvalidGatewayResponseException
     */
    public function saleTransaction() {
        //
        //
        //
        $deserializedPayload = null;
        $responseCode = self::invokeRpcAndDeserializeResponse(
            ApiEndpoints::sale(),
            ApiEncoding::FORM_URL,
            ApiEncoding::XML,
            $deserializedPayload
        );

        if ($deserializedPayload == null) {
            throw new InvalidGatewayResponseException(
                $responseCode
            );
        }

        //
        //  If a connection to the PayPOST API server was successfully
        //  established, the response code returned by the API server
        //  is HttpRpc.HTTP_OK.
        //
        if ($responseCode == Http::HTTP_OK) {
            try {
                return (new PayPostResponse())
                    ->xmlDeserialize($deserializedPayload)
                    ->validate(self::get("merchantid"), self::get("licencekey"));
            } catch (NamedValueNotFoundException $e) {
                return (new PayPostAuthenticationRequiredResponse())
                    ->xmlDeserialize($deserializedPayload)
                    ->validate();
            }
        } else {
            //  TODO: Determine better result to return
            return null;
        }
    }
}
