<?php

namespace CityPay\PayLink;

use CityPay\Lib\ApiMessage;
use CityPay\Lib\InvalidGatewayResponseException;
use CityPay\Lib\UnsupportedOperationException;
use CityPay\Encoding\Deserializable;
use CityPay\Encoding\Json\JsonDeserializable;


/**
 *
 */
class PayLinkPostbackNotice
    extends ApiMessage
    implements Deserializable,
    JsonDeserializable
{
    use \CityPay\Lib\NameValueComponent;
    use \CityPay\Lib\DataAccess;

    /**
     * @var array holding variable for returned data
     */
    private $array;

    /**
     * The amount field specifies the total amount of the Transaction in
     * Lowest Denomination Form, inclusive of any surcharge applied.
     *
     */
    private $amount;

    /**
     * The amount_initial field specifies the amount of the Transaction as
     * provided in the originating Paylink Token Request, in Lowest
     * Denomination Form.
     *
     */
    private $initialAmount;

    /**
     * The authcode field contains the Authorisation Code returned by the
     * Payment Card Acquirer if the Transaction was successful. An
     * Authorisation Code is an alpha-numeric string of characters and
     * may in some cases be an empty string.
     *
     */
    private $authcode;

    /**
     * The authorised field authoritatively indicates that the Transaction
     * was authorised and was successful ; the authResult and errorCode
     * fields may be used for more detailed information on the successful
     * or failed Transaction.
     *
     * This value will be true for a live as well as a test transaction.
     * It is therefore recommended to also check the mode field to ensure
     * your system handles test transactions appropriately.
     *
     */
    private $authorised;

    /**
     * The currency field contains the currency of the Transaction as an
     * ISO 4217 3 digit alpha-numeric value.
     *
     */
    private $currency;

    private $datetime;

    /**
     * The errorcode field contains an error code for the Transaction
     * Processing Request. An exhaustive list of error codes is available
     * from  API Response & Error Codes.
     *
     */
    private $errorCode;

    /**
     * The errorid field contains a Paylink internal error code for the
     * Transaction Processing Request in the form 000.000.000 for use in
     * correspondence in relation to Paylink support issues.
     *
     */
    private $errorId;

    /**
     * The errormessage field contains a textual, human-readable error
     * message for the Transaction Processing Request.
     *
     */
    private $errorMessage;

    /**
     * The identifier field contains the identifier provided for the
     * Transaction in the originating Paylink Token Request.
     *
     */
    private $identifier;

    /**
     * The merchantid contains the CityPay Merchant MID that the
     * Transaction Processing Request was processed with.
     *
     */
    private $merchantId;

    /**
     * The mode field specifies the mode of the transaction by returning
     * "live" or "test".
     *
     */
    private $mode;

    /**
     * The result field contains an integer result code that indicates the
     * outcome of the Transaction in more detail. The following result codes
     * are an exhaustive list and cater for all transaction types, including
     * electronic commerce, card present and mail-order telephone-order
     * transactions. Some result codes may not be relevant to the Merchant
     * Application or to processing in an online environment.
     *
     * <table>
     *   <thead><col /><col /><col />
     *      <tr><td>Value</td><td>Name</td><td>Description</td></tr>
     *   </thead>
     *   <tbody>
     *      <tr><td>0</td><td>Declined</td><td>
     *          Result code when a transaction is declined by the acquirer and
     *          no further processing is allowed.
     *      </td></tr>
     *      <tr><td>1</td><td>Accepted</td><td>
     *          Result code when a transaction is accepted by the acquirer.
     *      </td></td></tr>
     *      <tr><td>2</td><td>Rejected</td><td>
     *          The transaction was rejected due to an error.
     *      </td></tr>
     *      <tr><td>3</td><td>Not Attempted</td><td>
     *          The transaction was never attempted to be processed (default).
     *      </td></td></tr>
     *      <tr><td>4</td><td>Referred</td><td>
     *          Result code when a transaction has been referred for manual
     *          authorisation.
     *      </td></tr>
     *      <tr><td>5</td><td>Perform PIN Retry</td><td>
     *          Retry the PIN entry.
     *      </td></tr>
     *      <tr><td>6</td><td>Force Signature Verification</td><td>
     *          Force Signature Verification.
     *      </td></tr>
     *      <tr><td>7</td><td>Security Error</td><td>
     *          Security Error.
     *      </td></tr>
     *      <tr><td>8</td><td>Hold</td><td>
     *          Hold Transaction and recheck.
     *      </td></tr>
     *      <tr><td>9</td><td>Call Acquirer</td><td>
     *          Call Acquirer.
     *      </td></tr>
     *      <tr><td>10</td><td>Do Not Honour</td><td>
     *          Do Not Honour.
     *      </td></tr>
     *      <tr><td>11</td><td>Retain Card</td><td>
     *          Retain Card.
     *      </td></tr>
     *      <tr><td>12</td><td>Expired Card</td><td>
     *          The card has expired. This normally occurs when a valid expiry
     *          date is supplied and the card has been superseded by the issuer.
     *      </td></tr>
     *      <tr><td>13</td><td>Invalid Card No</td><td>
     *          The card number is invalid. This may occur after a valid LUHN check.
     *      </td></tr>
     *      <tr><td>14</td><td>Pin Tries Exceeded</td><td>
     *          The number of PIN entries have been exceeded.
     *      </td></tr>
     *      <tr><td>15</td><td>PIN Invalid</td><td>
     *          The PIN number entered is invalid.
     *      </td></tr>
     *      <tr><td>16</td><td>Pin Tries Exceeded</td><td>
     *          Authentication required for a transaction, in an e-commerce transaction,
     *          the system will need to refer to an ACS.
     *      </td></tr>
     *      <tr><td>17</td><td>Authentication Failed</td><td>
     *          Authentication is required for the transaction. The authorisation
     *          process failed.
     *      </td></tr>
     *      <tr><td>18</td><td>Verified</td><td>
     *          Is a business model which is used to verify that a card looks valid
     *          using AVS and CSC to check.
     *      </td></tr>
     *      <tr><td>19</td><td>Cancelled</td><td>
     *          A result which is returned when a pre-authorised transaction has been
     *          cancelled.
     *      </td></tr>
     *      <tr><td>20</td><td>Unknown</td><td>
     *          The unknown response is a value returned when the system has produced
     *          an error and the result cannot be determined.
     *      </td></tr>
     *   </tbody>
     */
    private $result;

    /**
     * Provided to allow the recipient Merchant Application to validate the
     * postback notice.
     *
     * TODO: consider best approach for this feature / functionality.
     *
     */
    private $licenceKey;

    /**
     * The status field contains a single digit status of the authorisation. The status
     * is a flag which determines how the transaction will be handled for settlement.
     * Ordinarily the value of this is 'O' which indicates that the transaction is open
     * for settlement. Merchants who have pre-authorisation enabled can determine that
     * the transaction is pre-authorised by seeing a 'P' in this response.
     *
     * <table>
     *  <thead>
     *      <col /><col /><col />
     *      <tr><td>Value</td><td>Name</td><td>Description</td></tr>
     *  </thead>
     *     <tbody>
     *     <tr><td>O</td><td>Open</td><td>
     *         The transaction is deemed as open for settlement and will be batched
     *         on the next scheduled batch run.
     *     </td></tr>
     *     <tr><td>A</td><td>Assigned</td><td>
     *         The transaction has been assigned to a batch and will be settled on
     *         the next settlement run.
     *     </td></tr>
     *     <tr><td>S</td><td>Settled</td><td>
     *         The transaction has been settled to the acquirer.
     *     </td></tr>
     *     <tr><td>X</td><td>Storage Failure</td><td>
     *         This is used internally to determine if there was a problem in storage,
     *         you would not expect this value from Paylink.
     *     </td></tr>
     *     <tr><td>D</td><td>Declined</td><td>
     *         The transaction has been declined and will not settle.
     *     </td></tr>
     *     <tr><td>R</td><td>Rejected</td><td>
     *         The transaction has been rejected and will not settle.
     *     </td></tr>
     *     <tr><td>P</td><td>PreAuth</td><td>
     *         The transaction has been pre-authorised. The transaction will require
     *         a completion or a cancellation to settle.
     *     </td></tr>
     *     <tr><td>P</td><td>Cancelled</td><td>
     *         The transaction has been cancelled and will not settle. Ordinarily a
     *         reversal would have occurred with the acquiring bank.
     *     </td></tr>
     *     <tr><td>E</td><td>Expired</td><td>
     *         The transaction has expired and has automatically been reversed. The
     *         transaction will not settle.
     *     </td></tr>
     *     <tr><td>I</td><td>Initialised</td><td>
     *         This is used internally to determine that the transaction has been
     *         initialised, you would not expect this value from Paylink.
     *     </td></tr>
     *     <tr><td>H</td><td>Wait For Auth</td><td>
     *         The transaction has been halted, waiting for authentication such as
     *         3DSecure.
     *     </td></tr>
     *     <tr><td>.</td><td>Hold</td><td>
     *         The transaction has been placed on hold and requires intervention.
     *         This is used by the grey listing of our fraud systems.
     *     </td></tr>
     *     <tr><td>?</td><td>Unknown</td><td>
     *         This is a status which cannot be determined, you would not expect
     *         this value from Paylink.
     *     </td></tr>
     *  </tbody>
     * </table>
     *
     */
    private $status;

    /**
     * If surcharge functionality is enabled for the Merchant MID, the surcharge
     * field contains the amount that has been added to the Transaction by way of
     * surcharge for the Payment Card selected to make the payment by the Payment
     * Card Holder.
     *
     * Surcharge functionality enables the Merchant to apply a fixed percentage
     * charge or a fixed amount charge against a Transaction to enable the Merchant
     * to recover their costs of Payment Card processing. The actual surcharges to
     * be applied to Transactions are associated with a particular Merchant MID.
     *
     */
    private $surcharge;

    /**
     * If surcharge functionality is enabled for the Merchant MID, the surcharge_rate
     * field contains the rate in which the surcharge was applied.
     *
     */
    private $surchargeRate;

    /**
     * The transno contains a Merchant-unique serial number for the Transaction. The
     * transno for a test transaction is -99; and for a Transaction Processing Request
     * that generated validation and request processing errors, -1.
     *
     */
    private $transNo;

    /**
     * The cardSchemeId field contains an identifier for the card scheme of the Payment
     * Card that was processed in the Transaction.
     *
     */
    private $cardSchemeId;

    /**
     * The cardScheme field contains a string describing the card scheme of the Payment
     * Card that was processed in the Transaction.
     */
    private $cardScheme;

    /**
     * The month of expiry of the card 1..12
     *
     */
    private $expMonth;

    /**
     * The year of expiry of the card i.e. 2014
     *
     */
    private $expYear;

    /**
     * The maskedPan field contains a masked version of the Card Number used by the
     * Card Holder when completing the Payment Form.
     *
     * The Payment Card Number is masked by providing the first 6 numbers which
     * identify the BIN Range for the Payment Card and the last 4 digits only; all
     * other digits are substituted by an asterisk. For example: 412312******2151.
     *
     */
    private $maskedPan;

    /**
     * The address field contains the Payment Card Holder address used by the Customer
     * when completing the Payment Form.
     *
     */
    private $address;

    /**
     * The country field contains the country code that the Payment Card Holder selected
     * when completing the Payment Form.
     *
     */
    private $country;

    /**
     * The email field contains the email address that the Payment Card Holder entered
     * when completing the Payment Form.
     *
     */
    private $email;

    /**
     * The firstname field contains the first name of the Payment Card Holder as entered
     * on the Payment Form.
     *
     */
    private $firstName;

    /**
     * The lastname field contains the last name of the Payment Card Holder as
     * entered in within the Payment Form.
     *
     */
    private $lastName;

    /**
     * The postcode field contains the postcode that the Payment Card Holder
     * entered when completing the Payment Form.
     *
     */
    private $postCode;

    /**
     * The title field contains the title of the Payment Card Holder as entered
     * on the Payment Form.
     *
     */
    private $title;

    /**
     * The AVSResponse field contains the result of address verification by the
     * upstream Payment Card Acquirer –
     *
     * <table>
     *     <thead>
     *         <col /><col /><col />
     *         <tr><td>Value</td><td>Description</td></tr>
     *     </thead>
     *     <tbody>
     *         <tr><td>' ' (Space)</td><td>
     *              No information
     *         </td></tr>
     *         <tr><td>A</td><td>
     *              Address matches, post code does not.
     *         </td></tr>
     *         <tr><td>B</td><td>
     *              Postal code not verified due to incompatible formats.
     *         </td></tr>
     *         <tr><td>C</td><td>
     *              Street address and Postal code not verified due to incompatible
     *              formats.
     *         </td></tr>
     *         <tr><td>D</td><td>
     *              Street address and postal codes match.
     *         </td></tr>
     *         <tr><td>E</td><td>
     *              AVS Error.
     *         </td></tr>
     *         <tr><td>G</td><td>
     *              Issuer does not participate in AVS.
     *         </td></tr>
     *         <tr><td>I</td><td>
     *              Address information verified for international transaction.
     *         </td></tr>
     *         <tr><td>M</td><td>
     *              Street address and Postal codes match for international transaction.
     *         </td></tr>
     *         <tr><td>N</td><td>
     *              No match on address or postcode.
     *         </td></tr>
     *         <tr><td>P</td><td>
     *              Postal codes match. Street address not verified due to to incompatible
     *              formats.
     *         </td></tr>
     *         <tr><td>R</td><td>
     *              System unavailable or Timed Out.
     *         </td></tr>
     *         <tr><td>S</td><td>
     *              Service not supported by issuer or processor.
     *         </td></tr>
     *         <tr><td>U</td><td>
     *              Address information unavailable.
     *         </td></tr>
     *         <tr><td>W</td><td>
     *              9 digit post code matches, Address does not.
     *         </td></tr>
     *         <tr><td>X</td><td>
     *              Postcode and Address match.
     *         </td></tr>
     *         <tr><td>Y</td><td>
     *              Address and 5 digit post code match
     *         </td></tr>
     *         <tr><td>Z</td><td>
     *              5 digit post code matches, Address does not
     *         </td></tr>
     *     </tbody>
     * </table>
     *
     * The meaning of some values are duplicated due to the source values from
     * MasterCard and Visa.
     *
     */
    private $avsResponse;

    /**
     * The CSCResponse field contains the result of the card security code checking
     * by the upstream Payment Card Acquirer –
     *
     * <table>
     *     <thead>
     *         <col /><col />
     *         <tr><td></td><td></td>
     *
     *
     *         </tr>
     *     </thead>
     *      <tbody>
     *          <tr><td>' ' (Space)</td><td>
     *              No information
     *          </td></tr>
     *          <tr><td>M</td><td>
     *              Card verification data matches.
     *          </td></tr>
     *          <tr><td>N</td><td>
     *              the card verification data was checked but did not match
     *          </td></tr>
     *          <tr><td>P</td><td>
     *              The card verification data was not processed.
     *          </td></tr>
     *          <tr><td>S</td><td>
     *              The card verification data should be on a card but the merchant
     *              indicates that it is not.
     *          </td></tr>
     *          <tr><td>U</td><td>
     *              Issuer not certified, did not provide the data or both.
     *          </td></tr>
     *      </tbody>
     * </table>
     *
     */
    private $cscResponse;

    /**
     * The cavv field is set to a value generated by the Card Issuer during
     * authentication. The cavv can provide evidence of Transaction
     * authentication during an online purchase. If the Transaction is
     * authenticated, this value should be kept to receive chargeback protection.
     *
     */
    private $cavv;

    /**
     * The eci field contains the Electronic Commerce Indicator (ECI) which
     * indicates the level of security applied when the cardholder provided
     * payment card details to the Merchant. Values for the ECI field are –
     *
     * <table>
     *     <thead>
     *         <tr><td>Value</td><td>Description</td></tr>
     *     </thead>
     *     <tbody>
     *         <tr><td>5</td><td>
     *             Set when a Transaction has been fully authenticate using 3DSecure.
     *         </td></tr>
     *         <tr><td>6</td><td>
     *             Set when an attempt is made to authenticate the cardholder using
     *             3DSecure, but the Payment Card Issuer or the Payment Card Holder were
     *             not participating in the 3DSecure scheme, or the Payment Card Issuer
     *             ACS was not able to respond to the request.
     *         </td></tr>
     *         <tr><td>7</td><td>
     *             Set when a Transaction has been fully authenticate using 3DSecure.
     *         </td></tr>
     *     </tbody>
     * </table>
     *
     */
    private $eci;

    /**
     * The authenticationResult field provides the outcome of 3DSecure
     * authentication as follows –
     *
     * <table>
     *     <thead>
     *
     *
     *     </thead>
     *     <tbody>
     *         <tr><td>Y</td><td>
     *             Authentication was successful.
     *         </td></tr>
     *         <tr><td>N</td><td>
     *             Authentication failed.
     *         </td></tr>
     *         <tr><td>U</td><td>
     *             Authentication could not be performed.
     *         </td></tr>
     *         <tr><td>A</td><td>
     *             Authentication was attempted but could not be performed.
     *         </td></tr>
     *     </tbody>
     * </table>
     *
     * Authentication that could not be performed or attempted may result in a
     * transaction that is authorised by the Acquirer. In such circumstances,
     * the Transaction may be subject to a higher risk profile and may not
     * benefit from the shift in fraudulent transaction liability from Merchant
     * to Customer that 3DSecure authentication generally provides.
     *
     * CityPay are able to control the workflow of your transaction if you
     * desire only fully authenticated transactions to be authorised.
     *
     */
    private $authenticationResult;


    /**
     * The cac field contains the results of the control path followed by
     * Paylink 3 in response to a Transaction Processing Request for a
     * Transaction which the Merchant Application indicated, by MID
     * configuration or through the originating Paylink Token Request,
     * that it requires to be associated with a Cardholder Account.
     *
     * The values returned in the cac field are as follows –
     *
     * <table>
     *     <thead>
     *
     *     </thead>
     *     <tbody>
     *         <tr><td>0</td><td>
     *              Indicates that no action has been taken.
     *         </td></tr>
     *         <tr><td>1</td><td><p>
     *              Indicates that the Cardholder Account referenced in the
     *              accountNo field of the originating Paylink Token Request
     *              was loaded and processed.
     *              </p><p>
     *              This result code is not usually generated in the context
     *              of a Transaction progressed using the Payment Form.
     *         </p></td></tr>
     *         <tr><td>2</td><td>
     *              Indicates that a new Cardholder Account, with the account
     *              reference returned in the cac_id, was created successfully
     *              in circumstances where the Merchant Application has not
     *              provided an identifier in the accountNo field of the
     *              originating Paylink Token Request.
     *         </td></tr>
     *         <tr><td>3</td><td>
     *              Indicates that the Cardholder Account with the identifier
     *              given by the accountNo field in the originating Paylink
     *              Token Request was updated with cardholder details such as
     *              the billing address and postal code submitted to Paylink 3
     *              by the Customer using the Payment Form.
     *         </td></tr>
     *         <tr><td>4</td><td><p>
     *              Indicates that the Cardholder Account with the identifier
     *              given by the accountNo field in the originating Paylink
     *              Token Request was deleted.
     *              </p><p>
     *              This result code is not usually generated in the context of
     *              a Transaction progressed using the Payment Form.
     *         </p></td></tr>
     *         <tr><td>5</td><td>
     *              Indicates that the Cardholder Account with the identifier
     *              given by the accountNo field in the originating Paylink
     *              Token Request.
     *         </td></tr>
     *     </tbody>
     * </table>
     *
     */
    private $cac;

    /**
     * The cac_id field contains the identifier of the Cardholder Account
     * associated with the Transaction.
     *
     */
    private $cacId;

    /**
     * (deprecated - md5 is known to be broken)
     *
     * The digest field contains the results of an MD5 Hash Function applied
     * to a subset of fields in the Transaction Processing Response encoded
     * as a Base64 string. The digest comprises the following parameters –
     *
     * <code>authcode</code>
     * <code>amount</code>
     * <code>errorcode</code>
     * <code>merchantid</code>
     * <code>transno</code>
     * <code>identifier</code>
     * <code>licencekey</code>
     *
     * This field is deprecated as MD5 is known to be broken. Please use sha256
     * instead.
     */
    private $digest;

    /**
     * The sha1 field contains the results of a SHA1 Hash Function applied to a
     * subset of fields in the Transaction Processing Response encoded as a
     * Base64 string. The digest comprises the following parameters –
     *
     * <code>authcode</code>
     * <code>amount</code>
     * <code>errorcode</code>
     * <code>merchantid</code>
     * <code>transno</code>
     * <code>identifier</code>
     * <code>licencekey</code>
     *
     * Although SHA1 is still in wide use, it is ending the end of its life
     * time, it is therefore recommended to use sha256 in preference.
     */
    private $sha1;

    /**
     * The sha256 field contains the results of a SHA256 Hash Function applied
     * to a subset of fields in the Transaction Processing Response encoded
     * as a Base64 string. The digest comprises the following parameters –
     *
     * <code>authcode</code>
     * <code>amount</code>
     * <code>errorcode</code>
     * <code>merchantid</code>
     * <code>transno</code>
     * <code>identifier</code>
     * <code>licencekey</code>
     *
     */
    private $sha256;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    private function calculateDigests()
    {
        $digestInput = $this->authcode
            . $this->amount
            . $this->errorCode
            . $this->merchantId
            . $this->transNo
            . $this->identifier
            . $this->licenceKey;

        return array(
            "md5" => base64_encode(md5($digestInput, true)),
            "sha1" => base64_encode(sha1($digestInput, true)),
            "sha256" => base64_encode(hash("sha256", $digestInput, true))
        );
    }

    /**
     * @return mixed
     */
    public function validate()
    {
        $digests = $this->calculateDigests();
        if ($this->sha256 == $digests["sha256"]) {
            return $this;
        } else {
            throw new InvalidGatewayResponseException(
                "PayLinkPostbackNotice::validate: hash digest conflict\n"
                . "    field  | packet digest cf. locally calculated digest\n"
                . "    sha256 | " . $this->sha256 . " cf. " . $digests["sha256"] . "\n"
            );
        }
    }

    public function deserialize($object)
    {
        throw new UnsupportedOperationException();
    }

    public function getCustomParam($name)
    {
        return self::getStringOrException($this->array, $name);
    }

    /**
     * @param $array
     */
    public function jsonDeserialize($object)
    {
        if (is_object($object)) {
            $this->array = get_object_vars($object);
        } else if (is_array($object)) {
            $this->array = $object;
        } else {
            throw new \CityPay\Encoding\ClassNoDeserializable();
        }

        $array = $this->array;
        $this->amount = self::getIntOrException($array, "amount");
        $this->authcode = self::getStringOrException($array, "authcode");
        $this->authorised = self::getBooleanOrException($array, "authorised");
        $this->currency = self::getStringOrException($array, "currency");
        $this->datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s+',
            self::getStringOrException($array, "datetime"),
            new \DateTimeZone("etc/utc")
        );
        $this->errorCode = self::getStringOrException($array, "errorcode");
        $this->errorId = self::getStringOrException($array, "errorid");
        $this->errorMessage = self::getStringOrException($array, "errormessage");
        $this->identifier = self::getStringOrException($array, "identifier");
        $this->merchantId = self::getIntOrException($array, "merchantid");
        $this->mode = self::getStringOrException($array, "mode");
        $this->result = self::getIntOrException($array, "result");
        $this->status = self::getStringOrException($array, "status");
        $this->transNo = self::getIntOrException($array, "transno");

        //
        //  Optional surcharge-related values
        //
        $this->initialAmount = self::getIntOrDefault($array, "amount_initial");
        $this->surcharge = self::getIntOrDefault($array, "surcharge");
        $this->surchargeRate = self::getIntOrDefault($array, "surcharge_rate");

        //
        //  Card details
        //
        $this->cardSchemeId = self::getStringOrException($array, "cardSchemeId");
        $this->cardScheme = self::getStringOrException($array, "cardScheme");
        $this->expMonth = self::getIntOrException($array, "expMonth");
        $this->expYear = self::getIntOrException($array, "expYear");
        $this->maskedPan = self::getStringOrException($array, "maskedPan");

        //
        //  Cardholder details
        //
        $this->address = self::getStringOrException($array, "address");
        $this->country = self::getStringOrException($array, "country");
        $this->email = self::getStringOrException($array, "email");
        $this->firstName = self::getStringOrException($array, "firstname");
        $this->lastName = self::getStringOrException($array, "lastname");
        $this->postCode = self::getStringOrException($array, "postcode");
        $this->title = self::getStringOrException($array, "title");

        //
        //  Address verifications
        //
        $this->avsResponse = self::getStringOrException($array, "AVSResponse");
        $this->cscResponse = self::getStringOrException($array, "CSCResponse");

        //
        //  Optional 3D Secure-related values
        //
        $this->cavv = self::getStringOrDefault($array, "cavv");
        $this->eci = self::getStringOrDefault($array, "eci");
        $this->authenticationResult = self::getStringOrDefault($array, "authenticationResult");

        //
        //  Cardholder Account
        //
        $this->cac = self::getIntOrException($array, "cac");
        $this->cacId = self::getStringOrException($array, "cac_id");

        //
        //  Payload verification
        //
        $this->digest = self::getStringOrException($array, "digest");
        $this->sha1 = self::getStringOrException($array, "sha1");
        $this->sha256 = self::getStringOrException($array, "sha256");

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getInitialAmount()
    {
        return $this->initialAmount;
    }

    public function getAuthcode()
    {
        return $this->authcode;
    }

    public function isAuthorised()
    {
        return $this->authorised;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorId()
    {
        return $this->errorId;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSurcharge()
    {
        return $this->surcharge;
    }

    public function getSurchargeRate()
    {
        return $this->surchargeRate;
    }

    public function getTransNo()
    {
        return $this->transNo;
    }

    public function getCardSchemeId()
    {
        return $this->cardSchemeId;
    }

    public function getCardScheme()
    {
        return $this->cardScheme;
    }

    public function getExpMonth()
    {
        return $this->expMonth;
    }

    public function getExpYear()
    {
        return $this->expYear;
    }

    public function getMaskedPan()
    {
        return $this->maskedPan;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPostCode()
    {
        return $this->postCode;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAvsResponse()
    {
        return $this->avsResponse;
    }

    public function getCscResponse()
    {
        return $this->cscResponse;
    }

    public function getCavv()
    {
        return $this->cavv;
    }

    public function getEci()
    {
        return $this->eci;
    }

    public function getAuthenticationResult()
    {
        return $this->authenticationResult;
    }

    public function getCac()
    {
        return $this->cac;
    }

    public function getCacId()
    {
        return $this->cacId;
    }

    public function getDigest()
    {
        return $this->digest;
    }

    public function getSha1()
    {
        return $this->sha1;
    }

    public function getSha256()
    {
        return $this->sha256;
    }

    public function licenceKey($licenseKey)
    {
        $this->licenceKey = $licenseKey;
        return $this;
    }
}