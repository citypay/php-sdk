<?php
namespace CityPay\PayPost;

use \CityPay\Lib\ApiMessage;
use \CityPay\Lib\InvalidGatewayResponseException;
use \CityPay\Encoding\Deserializable;
use \CityPay\Encoding\Json\JsonDeserializable;
use \CityPay\Encoding\Xml\XmlDeserializable;

/**
 * 
 */
class PayPostResponse
    extends ApiMessage
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    use \CityPay\Lib\DataAccess;
    
    /**
     * The authorisation code as returned by the card issuer or acquiring bank
     * for a successful transaction. Authorisation codes may contain alpha
     * numeric characters and may be an empty String.
     *
     */
    private $authCode;

    /**
     * A boolean definition that indicates that the transaction was authorised.
     * Attention should be referenced to the AuthResult, Mode and Error code
     * for accurate determination of the result.
     *
     * A transaction which is in test or live mode will return true, ensure
     * you check the Mode value is live!
     *
     */
    private $authorised;

    /**
     * An integer result that indicates the outcome of the transaction.
     *
     * <table>
     *     <thead>
     *          <col><col><col>
     *          <tr><td>Value</td><td>Name</td><td>Description</td></tr>
     *     </thead>
     *     <tbody>
     *          <tr><td>0</td><td>Declined</td><td>
     *              Result code when a transaction is declined by the
     *              acquirer and no further processing is allowed.
     *          </td></tr>
     *          <tr><td>1</td><td>Accepted</td><td>
     *              Result code when a transaction is accepted by the
     *              acquirer.
     *          </td></tr>
     *          <tr><td>2</td><td>Rejected</td><td>
     *              The transaction was rejected due to an error.
     *          </td></tr>
     *          <tr><td>3</td><td>Not Attempted</td><td>
     *              The transaction was never attempted to be processed
     *              (default).
     *          </td></tr>
     *          <tr><td>4</td><td>Referred</td><td>
     *              Result code when a transaction has been referred for
     *              manual authorisation.
     *          </td></tr>
     *          <tr><td>5</td><td>Perform PIN Retry</td><td>
     *              Retry the PIN entry
     *          </td></tr>
     *          <tr><td>6</td><td>Force Signature Verification</td><td>
     *              Force Signature Verification
     *          </td></tr>
     *          <tr><td>7</td><td>Hold</td><td>
     *              Hold Transaction and recheck
     *          </td></tr>
     *          <tr><td>8</td><td>Security Error</td><td>
     *              Security Error
     *          </td></tr>
     *          <tr><td>9</td><td>Call Acquirer</td><td>
     *              Call Acquirer
     *          </td></tr>
     *          <tr><td>10</td><td>Do Not Honour</td><td>
     *              Do Not Honour
     *          </td></tr>
     *          <tr><td>11</td><td>Retain Card</td><td>
     *              Retain Card
     *          </td></tr>
     *          <tr><td>12</td><td>Expired Card</td><td>
     *              The card has expired. This normally occurs when a valid
     *              expiry date is supplied and the card has been superseded
     *              by the issuer.
     *          </td></tr>
     *          <tr><td>13</td><td>Invalid Card No</td><td>
     *              The card number is invalid. This may occur after a valid
     *              LUHN check.
     *          </td></tr>
     *          <tr><td>14</td><td>Pin Tries Exceeded</td><td>
     *              The number of PIN entries have been exceeded.
     *          </td></tr>
     *          <tr><td>15</td><td>PIN Invalid</td><td>
     *              The PIN number entered is invalid.
     *          </td></tr>
     *          <tr><td>16</td><td>Authentication Required</td><td>
     *              Authentication required for a transaction, in an
     *              e-commerce transaction, the system will need to refer
     *              to an ACS.
     *          </td></tr>
     *          <tr><td>17</td><td>Authentication Failed</td><td>
     *              Authentication is required for the transaction. The
     *              authorisation process failed.
     *          </td></tr>
     *          <tr><td>18</td><td>Verified</td><td>
     *              Is a business model which is used to verify that a
     *              card looks valid using AVS and CSC to check.
     *          </td></tr>
     *          <tr><td>19</td><td>Cancelled</td><td>
     *              A result which is returned when a pre-authorised
     *              transaction has been cancelled.
     *          </td></tr>
     *          <tr><td>20</td><td>Unknown</td><td>
     *              The unknown response is a value returned when the
     *              system has produced an error and the result cannot
     *              be determined.
     *          </td></tr>
     *     </tbody>
     * </table>
     *
     */
    private $authResult;


    /**
     * Denotes the mode of the transaction by returning a value of live or
     * test
     *
     */
    private $mode;


    /**
     * The amount of the transaction processed.
     */
    private $amount;

    /**
     * The currency the transaction was processed in. This is an ISO 4217 3
     * digit currency value.
     *
     * This value is an XML attribute of amount
     *
     */
    private $currency;

    /**
     * The identifier provided within the request.
     *
     * This value may be escaped as CDATA to prevent XML injection
     *
     */
    private $identifier;

    /**
     * The response code as defined in Error Codes Reference for example 000
     * is an accepted live transaction whilst 001 is an accepted test
     * transaction. Error codes identify the source of success and failure.
     *
     * Codes that start with an alpha character i.e. C001 indicate a type of
     * error such that an error starting with C is a card validation error.
     *
     * This allows your workflow to determine how to continue where
     * reprocessing may be required.
     *
     * In an XML response, this value will be contained in an Error element.
     *
     */
    private $code;

    /**
     * Supplementary field that only appears to be represented in an
     * XML response generated by the PayPOST API.
     */
    private $errorId;

    /**
     * Supplementary field that only appears to be represented in an
     * XML response generated by the PayPOST API.
     * 
     * @var string
     */
    private $errorLocation;

    /**
     * The response message which provides further information other than the Code response.
     *
     * In an XML response, this value will be contained in an Error element
     * 
     * @var string
     */
    private $message;

    /**
     * Supplementary field that only appears to be represented in an
     * XML response generated by the PayPOST API.
     * 
     * @var string
     */
    private $errorSystem;
    
    /**
     * The payment card scheme involved in performance of the transaction.
     * 
     * @var string 
     */
    private $scheme;
    
    /**
     *
     * @var char 
     */
    private $status;

    /**
     * The resulting transaction number, ordered incrementally for every
     * merchantid. The value will default to -1 for transactions that
     * cannot be forwarded r processing i.e. access control failures.
     *
     */
    private $transNo;

    /**
     * The result of CSC checking
     *
     * The CSC response codes determine the result of transactions processed
     * using the Card Security Code fraud system.
     *
     * <table>
     *      <thead>
     *          <col><col><col>
     *          <tr><td>Value</td><td>Description</td></tr>
     *      </thead>
     *      <tbody>
     *          <tr><td>(Space)</td><td>
     *              Result code when a transaction is declined by the
     *              acquirer and no further processing is allowed.
     *              No information
     *          </td></tr>
     *          <tr><td>M</td><td>
     *              Card verification data matches.
     *          </td></tr>
     *          <tr><td>N</td><td>
     *              The card verification data was checked but did not match.
     *          </td></tr>
     *          <tr><td>P</td><td>
     *              The card verification data was not processed.
     *          </td></tr>
     *          <tr><td>S</td><td>
     *              The card verification data should be on a card but the
     *              merchant indicates that it is not.
     *          </td></tr>
     *          <tr><td>U</td><td>
     *              Issuer not certified, did not provide the data or both.
     *          </td></tr>
     *               *          <tr><td>M</td><td>
     *              Card verification data matches.
     *          </td></tr>
     *               *          <tr><td>M</td><td>
     *              Card verification data matches.
     *          </td></tr>
     *      </tbody>
     * </table>
     *
     */
    private $cscResult;

    /**
     * The result of AVS checking
     *
     * The following AVS response codes as used by CityPay to determine
     * result of processing using the Address Verification System.
     *
     *
     * <table>
     *      <thead>
     *          <col><col><col>
     *          <tr><td>Value</td><td>Description</td></tr>
     *      </thead>
     *      <tbody>
     *          <tr><td>(Space)</td><td>
     *              No information
     *          </td></tr>
     *          <tr><td>A</td><td>
     *              Address matches, post code does not.
     *          </td></tr>
     *          <tr><td>B</td><td>
     *              Postal code not verified due to incompatible formats.
     *          </td></tr>
     *          <tr><td>C</td><td>
     *              Street address and Postal code not verified due to
     *              incompatible formats.
     *          </td></tr>
     *          <tr><td>D</td><td>
     *              Street address and postal codes match.
     *          </td></tr>
     *          <tr><td>E</td><td>
     *              AVS Error.
     *          </td></tr>
     *          <tr><td>G</td><td>
     *              Issuer does not participate in AVS.
     *          </td></tr>
     *          <tr><td>I</td><td>
     *              Address information verified for international
     *              transaction.
     *          </td></tr>
     *          <tr><td>M</td><td>
     *              Street address and Postal codes match for international
     *              transaction.
     *          </td></tr>
     *          <tr><td>N</td><td>
     *              No match on address or postcode.
     *          </td></tr>
     *          <tr><td>P</td><td>
     *              Postal codes match. Street address not verified due to
     *              incompatible formats.
     *          </td></tr>
     *          <tr><td>R</td><td>
     *              System unavailable or Timed Out.
     *          </td></tr>
     *          <tr><td>S</td><td>
     *              Service not supported by issuer or processor.
     *          </td></tr>
     *          <tr><td>U</td><td>
     *              Address information unavailable.
     *          </td></tr>
     *          <tr><td>W</td><td>
     *              9 digit post code matches, Address does not.
     *          </td></tr>
     *          <tr><td>X</td><td>
     *              Postcode and Address match.
     *          </td></tr>
     *          <tr><td>Y</td><td>
     *              Address and 5 digit post code match.
     *          </td></tr>
     *          <tr><td>Z</td><td>
     *              5 digit post code matches, Address does not.
     *          </td></tr>
     *      </tbody>
     * </table>
     *
     * The meaning of some values are duplicated due to the source values
     * from MasterCard and Visa
     *
     */
    private $avsResult;

    /**
     * MD5 hash of the response data
     *
     */
    private $digest;

    /**
     * The card number masked using a PCI acceptable masking process. Displays
     * the bin range using the first 6 numbers and the last 4 digits. All other
     * digits are masked with an asterisk. e.g.412312******2151.
     *
     */
    private $maskedPan;

    /**
     * The CAVV is a cryptographic value derived by the Card Issuer during
     * payment authentication that can provide evidence of the results of
     * payment authentication during an online purchase. This value is
     * required in order for the Merchant to receive chargeback protection
     * using 3D-Secure. This value is only returned if successfully
     * authenticated.
     *
     */
    private $cavv;

    /**
     * The Electronic Commerce Indicator (ECI)
     *
     * Possible ECI values are:
     *
     * <table>
     *      <thead>
     *          <tr><td>PARes Status Field</td><td>Description</td>
     *          <td>MasterCard ECI Value</td><td>Visa ECI Value</td></tr>
     *      </thead>
     *      <tbody>
     *          <tr><td>Y</td><td>
     *              Cardholder was successfully authenticated.
     *          </td><td>02</td><td>05</td></tr>
     *          <tr><td>A</td><td>
     *              Authentication could not be performed but a proof of
     *              authentication attempt was provided.
     *          </td><td>01</td><td></td></tr>
     *          <tr><td>N</td><td>
     *              Cardholder authentication failed.
     *          </td><td>-</td><td>-</td></tr>
     *          <tr><td>U</td><td>
     *              Authentication could not be performed due to a technical
     *              error or other problem.
     *          </td><td>07</td><td>07</td></tr>
     *          <tr><td>Y</td><td>
     *              Cardholder was successfully authenticated.
     *          </td><td></td><td></td></tr>
     *          <tr><td>Y</td><td>
     *              Cardholder was successfully authenticated.
     *          </td><td></td><td></td></tr>
     *      </tbody>
     * </table>
     *
     * Value '07' indicates that the transaction will be treated as
     * e-Commerce (SSL encryption only). The Visa and MasterCard 3-D
     * Secure programs were unable to verify if the cardholder is
     * enrolled. The payment card used for this transaction is deemed
     * ineligible for 3-D Secure processing. Consequently, the merchant
     * may not claim the right of a liability shift. However, this does
     * not imply that the card cannot be accepted.
     *
     * To automatically reject transactions that are not successfully
     * authenticated, please contact your account manager.
     *
     */
    private $eci;
    
    function __construct() {
        parent::__construct();
    }

    protected function this() { return $this; }

    public function getAuthCode() {
        return $this->authCode;
    }

    public function isAuthorised() {
        return $this->authorised;
    }

    public function getAuthResult() {
        return $this->authResult;
    }

    public function getMode() {
        return $this->mode;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getCode() {
        return $this->code;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getTransNo() {
        return $this->transNo;
    }

    public function getCscResult() {
        return $this->cscResult;
    }

    public function getAvsResult() {
        return $this->avsResult;
    }

    public function getDigest() {
        return $this->digest;
    }

    public function getMaskedPan() {
        return $this->maskedPan;
    }

    public function getCavv() {
        return $this->cavv;
    }

    public function getEci() {
        return $this->eci;
    }
    
    /**
     * 
     * @param type $object
     * @return \CityPay\PayPost\PayPostResponse
     * @throws \CityPay\Encoding\ClassNoDeserializable
     */
    public function deserialize($object) {
        if (is_object($object)) {
            $array = get_object_vars($object);
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new \CityPay\Encoding\ClassNotDeserializable();
        }

        $this->authCode = self::getStringOrException($array, "Authcode");
        $this->authorised = self::getBooleanOrException($array, "Authorised");
        $this->authResult = self::getIntOrException($object, "AuthResult");
        $this->mode = self::getStringOrException($array, "Mode");
        $this->amount = self::getIntOrException($array, "Amount");
        $this->currency = self::getStringOrException($array, "Currency");
        $this->identifier = self::getStringOrException($array, "Identifier");
        $this->code = self::getStringOrException($array, "Code");
        $this->message = self::getStringOrException($array, "Message");
        $this->transNo = self::getIntOrException($array, "TransNo");

        //
        //  Card details
        //
        $this->maskedPan = self::getStringOrException($array, "MaskedPan");

        //
        //  Address verifications
        //
        $this->cscResult = self::getStringOrException($array, "CSCResult");
        $this->avsResult = self::getStringOrException($array, "AVSResult");

        //
        //  Optional 3D Secure-related values
        //
        $this->cavv = self::getStringOrDefault($array, "CAVV");
        $this->eci = self::getStringOrDefault($array, "ECI");

        //
        //  Payload verification
        //
        $this->digest = self::getStringOrException($array, "digest");

        return this();
    }
    
    /**
     * 
     * @param type $object
     * @return \CityPay\PayPost\PayPostResponse
     * @throws \CityPay\Encoding\ClassNoDeserializable
     */
    public function jsonDeserialize($object) {
        if (!is_array($object)) {
            throw new \InvalidArgumentException();
        }
        
        return $this->deserialize($object);
    }
    
    /**
     * 
     * @param type $object
     * @return type
     */
    public function xmlDeserialize($object) {
        if (!($object instanceof \SimpleXMLElement)) {
            throw new \InvalidArgumentException();
        }
        
        $this->authCode = self::getStringOrException($object, "Authcode");
        $this->authorised = self::getBooleanOrException($object, "Authorised");
        $this->authResult = self::getIntOrException($object, "AuthResult");
        $this->mode = self::getStringOrException($object, "Mode");

        $amount = self::getValueOrException($object, "Amount");
        $this->amount = (string) $amount;
        $this->currency = self::getStringOrException(
            $amount->attributes(),
            "currency"
        );
        
        $this->identifier = self::getStringOrException($object, "Identifier");
        
        $error = self::getValueOrException($object, "Error");
        $this->code = self::getStringOrException($error, "Code");
        $this->message = self::getStringOrException($error, "Message");
        $this->errorLocation = self::getStringOrException($error, "Location");
        $this->errorSystem = self::getStringOrException($error, "System");
        
        $this->transNo = self::getIntOrException($object, "TransNo");
        
        $this->status = self::getStringOrDefault($object, "Status");

        //
        //  Card details
        //
        $this->maskedPan = self::getStringOrException($object, "MaskedPan");
        $this->scheme = self::getStringOrDefault($object, "Scheme");

        //
        //  Address verifications
        //
        $this->cscResult = self::getStringOrException($object, "CSCResult");
        $this->avsResult = self::getStringOrException($object, "AVSResult");

        //
        //  Optional 3D Secure-related values
        //
        $this->cavv = self::getStringOrDefault($object, "CAVV");
        $this->eci = self::getStringOrDefault($object, "ECI");

        //
        //  Payload verification
        //
        $this->digest = self::getStringOrException($object, "Digest");

        return $this->this();
    }
    
    /**
     * 
     * @param string $merchantId
     * @param string $licenceKey
     * @return PayPostResponse
     */
    public function validate(
        $merchantId,
        $licenceKey
    ) {
        $digestInput = $this->authCode
            .$this->amount
            .$this->code
            .$merchantId
            .$this->transNo
            .$this->identifier
            .$licenceKey;
        
        if ($this->digest == base64_encode(md5($digestInput, true))) {
            return $this->this();
        } else {
            throw new InvalidGatewayResponseException();
        }
    }
}
