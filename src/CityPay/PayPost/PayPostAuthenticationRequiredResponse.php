<?php
namespace CityPay\PayPost;

use \CityPay\Lib\ApiMessage;
use \CityPay\Encoding\Deserializable;
use \CityPay\Encoding\Json\JsonDeserializable;
use \CityPay\Encoding\Xml\XmlDeserializable;

/**
 * 
 */
class PayPostAuthenticationRequiredResponse
    extends ApiMessage
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    use \CityPay\Lib\DataAccess;

    /**
     * The URL for the payment card issuers' Access Control Server (ACS). 
     */
    private $acsUrl;

    /**
     * The Payment Authentication Request packet which should be sent to the
     * URL of the Access Control Server (ACS) to establish the
     * authentication session
     */
    private $paReq;

    /**
     * Merchant Data which should be sent back unaltered in the next request
     * to the API server. The data contains an identity of the original
     * requested transaction to allow continuance of the original request.
     */
    private $md;
    
    /**
     * 
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 
     */
    protected function this() { return $this; }

    /**
     * 
     */
    public function getAcsUrl() {
        return $this->acsUrl;
    }

    /**
     * 
     */
    public function getPaReq() {
        return $this->paReq;
    }

    /**
     * 
     */
    public function getMd() {
        return $this->md;
    }
    
    /**
     * Initialise the a PayPostAuthenticationRequired object in accordance
     * with the relevant transport neutral schema.
     * 
     * @param type $object
     */
    public function deserialize($object) {
        /*if (is_object($object)) {
            $array = get_object_vars($object);
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new ClassNotDeserializable();
        }*/

        $this->acsUrl = self::getStringOrException($object, "ACSUrl");
        $this->paReq = self::getStringOrException($object, "PAReq");
        $this->md = self::getStringOrException($object, "MD");
        return $this->this();
    }
    
    /**
     * Initialise the a PayPostAuthenticationRequired object in accordance
     * with the relevant XML schema.
     * 
     * @param type $object
     * @return \CityPay\PayPost\PayPostResponse
     * @throws \CityPay\Encoding\ClassNoDeserializable
     */
    public function jsonDeserialize($object) {
        return $this->deserialize($object);
    }
    
    /**
     * Initialise the a PayPostAuthenticationRequired object in accordance
     * with the relevant XML schema.
     * 
     * @param type $object
     * @return \CityPay\PayPost\PayPostResponse
     * @throws \CityPay\Encoding\ClassNoDeserializable
     */
    public function xmlDeserialize($object) {
        return $this->deserialize($object);
    }
    
    /**
     * Validates the contents of a PayPostAuthenticationRequired object.
     * 
     * @return PayPostAuthenticationRequiredResponse
     */
    public function validate() {
        return $this->this();
    }
}

