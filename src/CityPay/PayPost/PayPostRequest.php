<?php
namespace CityPay\PayPost;

use CityPay\Lib\Rpc\Http;
use CityPay\Lib\ApiRequest;
use CityPay\Lib\ApiEncoding;
use CityPay\Lib\InvalidGatewayResponseException;

/**
 * 
 */
class PayPostRequest
    extends ApiRequest
{
    use CardholderAccount;
    
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
     */
    protected function this() {
        return $this;
    }
    
    const AVS_ADDRESS_POLICY_USE_DEFAULT = 0x00,
        AVS_ADDRESS_POLICY_ENFORCE_AVS_ADDRESS_MATCH = 0x01,
        AVS_ADDRESS_POLICY_BYPASS_AVS_ADDRESS_MATCH = 0x02;

    const AVS_POSTAL_CODE_POLICY_USE_DEFAULT = 0x00,
        AVS_POSTAL_CODE_POLICY_ENFORCE_AVS_POSTAL_CODE_MATCH = 0x01,
        AVS_POSTAL_CODE_POLICY_BYPASS_AVS_POSTAL_CODE_MATCH = 0x02;

    const CSV_POLICY_USE_DEFAULT = 0x00,
        CSV_POLICY_ENFORCE_CSC_MATCH = 0x01,
        CSV_POLICY_BYPASS_CSC_MATCH = 0x02;

    const PRE_AUTHORISATION_POLICY_USE_DEFAULT = 0x00,
        PRE_AUTHORISATION_POLICY_PRE_AUTHORISE = 0x01,
        PRE_AUTHORISATION_POLICY_AUTO_COMPLETE = 0x02;
    
    /**
     * 
     * @param type $avsAddressPolicy
     * @return type
     */
    public function avsAddressPolicy(
        $avsAddressPolicy
    ) {
        self::set("match_avsa", avsAddressPolicy.getValue());
        return $this->this();
    }

    /**
     * 
     * @param type $avsPostalCodePolicy
     * @return type
     */
    public function avsPostalCodePolicy(
        $avsPostalCodePolicy
    ) {
        self::set("match_avsp", avsPostalCodePolicy.getValue());
        return $this->this();
    }

    /**
     * 
     * @param type $cscPolicy
     * @return type
     */
    public function cscPolicy(
        $cscPolicy
    ) {
        self::set("match_csc", cscPolicy.getValue());
        return $this->this();
    }

    /**
     * 
     * @param type $preAuthorisationPolicy
     * @return type
     */
    public function preAuth(
        $preAuthorisationPolicy
    ) {
        self::set("pre_auth", $preAuthorisationPolicy);
        return $this->this();
    }
    
    /**
     * The amount of the relevant transaction expressed in "lowest
     * denomination form" ("LDF") to a maxmium of 12 digits.
     * 
     * If passed as a string, the string should be an integer value
     * and should not include any decimal points, or any other separators.
     * 
     * @param int $amount
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function amount(
        $amount
    ) {
        self::set("amount", $amount);
        return $this->this();
    }
    
    /**
     * The card holder's registered address for the card. AVS ensures that the
     * card holder has supplied and knows the correct billing address. The
     * address is supplied to the online authorisation and a match decision is
     * made against the registered value with the card issuer. The gateway
     * allows a policy which describes the business rules which allow you to
     * select whether to reject or accept transactions that do not match. Not
     * all cards belong to the AVS scheme and it the results can depend on the
     * locality where the card details are registered. We therefore cannot
     * guarantee that this option will always be realised in transaction
     * processing.
     *
     * The address details may also be supplied in individual lines such as
     * avsaddress1, avsaddress2,  avsaddress3,  avsaddress4,  avsaddress5.
     *
     * OPTIONAL value, unless AVS is required.
     *
     * @param address
     * @return
     */
    public function billToAddress(
        $address
    ) {
        self::set("billto_address", $address);
        return $this->this();
    }

    /**
     * The card holder's email address. The email address may be used to
     * trigger email notifications of the success of a transaction or for
     * transaction searching.
     *
     * OPTIONAL value.
     *
     * @return
     */
    public function billToEmailAddress(
        $emailAddress
    ) {
        self::set("billto_email", $emailAddress);
        return $this->this();
    }

    /**
     * The card holder's name as it appears on the card.
     *
     * OPTIONAL value.
     *
     * @param string name
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function billToName(
        $name
    ) {
        self::set("billto_name", $name);
        return $this->this();
    }

    /**
     * The card holder's postcode for AVS checking. The gateway allows a policy
     * which describes business rules which allow you to select whether to
     * reject or accept transactions that do not match the postcode.
     *
     * Not all cards belong to the AVS scheme. Some countries (such as Ireland)
     * also do not have postcodes. We therefore cannot guarantee that this
     * option is failsafe. Where a card or country is not participating in the
     * scheme, the gateway will process without AVS enabled.
     *
     * OPTIONAL value, unless AVS is required.
     *
     * @param postCode
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function billToPostCode(
        $postCode
    ) {
        self::set("billto_postcode", $postCode);
        return $this->this();
    }

    /**
     * The billing telephone number.
     *
     * OPTIONAL value.
     *
     * @param string telephoneNo
     * @return PayPostResponse
     */
    public function billToTelephoneNo(
        $telephoneNo
    ) {
        self::set("billto_tel", $telephoneNo);
        return $this->this();
    }

    /**
     * The payment card number to be charged, or refunded.
     * 
     * @param string $cardNumber
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function cardNumber(
        $cardNumber
    ) {
        self::set("cardnumber", $cardNumber);
        return $this->this();
    }

    /**
     * The Card Security Code (CSC) (also known as CV2/CVV2) is a number that
     * can be found on the back of the card (Amex is on the front). This helps
     * to identify that the card holder has the card in their possession as it
     * is not available within the chip or magnetic swipe. The CSC number aids
     * fraud prevention in mail order and internet payments.
     *
     * The CSC works by forwarding the code to the acquiring bank and card
     * issuer to validate whether the code matches that is on record. Business
     * rules are available on your account to identify whether to accept or
     * decline transactions based on an incorrect response.
     *
     * The Payment Card Industry (PCI) requires that at no stage of a
     * transaction should the CSC be stored. This applies to all entities
     * handling card data. We do not store the CSC number or use it in any
     * hashing process. We also have no method of retrieving the value provided
     * for processing.
     *
     * OPTIONAL unless CSC checking is required.
     *
     * @param string csc
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function csc(
        $csc
    ) {
        self::set("csc", $csc);
        return $this->this();
    }

    /**
     * The ISO 4217 3 digit currency code of the transaction to process as.
     * The currency value will default to the currency set for the merchant
     * account if it is not supplied. Supplying a currency which is not
     * available for your account will result in an error.
     *
     * OPTIONAL value.
     *
     * @param currency
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function currency(
        $currency
    ) {
        self::set("currency", $currency);
        return $this->this();
    }
    
    /**
     * The expiry month of the relevant payment card, with a value of
     * between 1 (January) and 12 (December).
     * 
     * @param integer $expiryMonth
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function expiryMonth(
        $expiryMonth
    ) {
        self::set("expmonth", $expiryMonth);
        return $this->this();
    }
    
    /**
     * The expiry year of the relevant payment card, expressed in terms
     * of a two digit year code, or a 4 digit year code.
     * 
     * @param inter $expiryYear
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function expiryYear(
        $expiryYear
    ) {
        self::set("expyear", $expiryYear);
        return $this->this();
    }

    /**
     * The issue number of the card. Required for some Maestro and Visa cards.
     *
     * OPTIONAL value.
     *
     * @param issueNumber
     * @return
     */
    public function issueNumber(
        $issueNumber
    ) {
        self::set("issuenumber", $issueNumber);
        return $this->this();
    }

    /**
     * The month of the prevalid or start date of the card. The month value
     * should be a numerical value between 1 and 12.
     *
     * OPTIONAL unless required by the card scheme.
     *
     * @param preValidMonth
     * @return
     */
    public function preValidMonth(
        $preValidMonth
    ) {
        self::set("premonth", $preValidMonth);
        return $this->this();
    }
    
    /**
     * An identifier to uniquely identify this transaction.
     *
     * The identifier may be used to perform post processing actions
     * against a transaction and to aid in reconciliation of transactions,
     * and should be an ASCII string and must be less than 50 characters
     * in length.
     * 
     * @param string $identifier
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function identifier(
        $identifier
    ) {
        self::set("identifier", $identifier);
        return $this->this();
    }

    /**
     * The year of the pre valid or start date of the card. The year value
     * may be in a 4 digit year value or a 2 digit value.
     *
     * OPTIONAL unless required by the card scheme.
     *
     * @param preValidYear
     * @return
     */
    public function preValidYear(
        $preValidYear
    ) {
        self::set("preyear", $preValidYear);
        return $this->this();
    }
    
    /**
     * The licence key associated with the merchant account to be used to
     * process the transaction.
     * 
     * @param string $licenceKey
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function licenceKey(
        $licenceKey
    ) {
        self::set("licencekey", $licenceKey);
        return $this->this();
    }
    
    /**
     * The MID for the merchant account to be used to process the transaction. 
     * 
     * @param string $merchantId
     * @return \CityPay\PayPost\PayPostRequest
     */
    public function merchantId(
        $merchantId
    ) {
        self::set("merchantid", $merchantId);
        return $this->this();
    }

    /**
     * This setting allows a transaction to be processed to the test gateway.
     * The default value is true, forwarding all transactions to the test
     * gateway.
     *
     * This value should be set to false to perform a live transaction.
     *
     * Transactions will be returned with an auth code of A12345 and an
     * error code of 001 if the account is set to test mode.
     *
     * A merchant account may also be set to process test transactions only,
     * please make sure you inform us if your account appears to be providing
     * test results.
     *
     * REQUIRED value.
     *
     * @param test
     * @return
     */
    public function test(
        $test
    ) {
        self::set("test", $test);
        return $this->this();
    }

    /**
     * Information related to the transaction. Addition of further information
     * can be added to this field which will display in reporting. The value
     * length must not exceed 50 characters.
     *
     * OPTIONAL value.
     *
     * @param transInfo
     * @return
     */
    public function transInfo(
        $transInfo
    ) {
        self::set("trans_info", $transInfo);
        return $this->this();
    }

    /**
     * The transaction number of the transaction to look up and complete, or
     * cancel. If an empty value is supplied then an identifier value must be
     * supplied.
     *
     * @param transNo
     * @return
     */
    public function transNo(
        $transNo
    ) {
        self::set("transno", $transNo);
        return $this->this();
    }

    /**
     * The type of transaction being submitted. If no value is supplied, the
     * default type will be used i.e. internet sale.
     *
     * OPTIONAL, unless instructed.
     *
     * @param transType
     * @return
     */
    public function transType(
        $transType
    ) {
        self::set("trans_type", $transType);
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
        $payload = null;
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
            $authenticationRequired = (
                isset($deserializedPayload->authenticationRequired)
                    ? $deserializedPayload->authenticationRequired : false
            );
            if ($authenticationRequired) {
                return (new PayPostAuthenticationRequiredResponse())
                    ->xmlDeserialize($deserializedPayload)
                    ->validate();
            } else {
                return (new PayPostResponse())
                    ->xmlDeserialize($deserializedPayload)
                    ->validate(self::get("merchantid"), self::get("licencekey"));
            }
        } else {
            //  TODO: Determine better result to return
            return null;
        }
    }

    /**
     * 
     * @return \CityPay\Lib\ApiMessage
     * @throws InvalidGatewayResponseException
     */
    public function refundTransaction() {
        //
        //
        //
        $deserializedPayload = null;
        $responseCode = self::invokeRpcAndDeserializeResponse(
            ApiEndpoints::refund(),
            ApiEncoding::FORM_URL,
            ApiEncoding::XML,
            $deserializedPayload
        );

        if ($deserializedPayload == null) {
            throw new InvalidGatewayResponseException();
        }

        //
        //  If a connection to the PayPOST API server was successfully
        //  established, the response code returned by the API server
        //  is HttpRpc.HTTP_OK.
        //
        if ($responseCode == Http::HTTP_OK) {
            return new PayPostResponse()
                .xmlDeserialize($deserializedPayload)
                .validate(self::get("merchantid"), self::get("licencekey"));
        } else {
            //  TODO: Determine better result to return
            return null;
        }
    }

    /**
     * 
     * @return \CityPay\Lib\ApiMessage
     * @throws InvalidGatewayResponseException
     */
    public function cancelTransaction() {
        //
        //
        //
        $deserializedPayload = null;
        $responseCode = self::invokeRpcAndDeserializeResponse(
            ApiEndpoints::cancel(),
            ApiEncoding::FORM_URL,
            ApiEncoding::XML,
            $deserializedPayload
        );

        if ($deserializedPayload == null) {
            throw new InvalidGatewayResponseException();
        }

        //
        //  If a connection to the PayPOST API server was successfully
        //  established, the response code returned by the API server
        //  is HttpRpc.HTTP_OK.
        //
        if ($responseCode == Http::HTTP_OK) {
            return (new PayPostResponse())
                ->xmlDeserialize($deserializedPayload)
                ->validate(self::get("merchantid"), self::get("licencekey"));
        } else {
            //  TODO: Determine better result to return
            return null;
        }
    }

    /**
     * 
     * @return \CityPay\Lib\ApiMessage
     * @throws InvalidGatewayResponseException
     */
    public function completeTransaction() {
        //
        //
        //
        $deserializedPayload = null;
        $responseCode = self::invokeRpcAndDeserializeResponse(
            ApiEndpoints::complete(),
            ApiEncoding::FORM_URL,
            ApiEncoding::XML,
            $deserializedPayload
        );

        if ($deserializedPayload == null) {
            throw new InvalidGatewayResponseException();
        }

        //
        //  If a connection to the PayPOST API server was successfully
        //  established, the response code returned by the API server
        //  is HttpRpc.HTTP_OK.
        //
        if ($responseCode == Http::HTTP_OK) {
            return (new PayPostResponse())
                ->xmlDeserialize($deserializedPayload)
                ->validate(self::get("merchantid"), self::get("licencekey"));
        } else {
            //  TODO: Determine better result to return
            return null;
        }
    }
}

