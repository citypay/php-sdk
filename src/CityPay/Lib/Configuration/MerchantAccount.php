<?php
namespace CityPay\Lib\Configuration;

use CityPay\Encoding\Deserializable;
use CityPay\Encoding\Json\JsonDeserializable;
use CityPay\Encoding\Xml\XmlDeserializable;
use CityPay\Encoding\ClassNotDeserializable;

/**
 * 
 */
class MerchantAccount
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    use \CityPay\Lib\DataAccess;
    
    /**
     *
     * @var string 
     */
    private $accountName;
    
    /**
     *
     * @var string 
     */
    private $mid;
    
    /**
     *
     * @var string 
     */
    private $licenceKey;
    
    public function this() { return $this; }
    
    /**
     * 
     * @return type
     */
    public function getAccountName() {
        return $this->accountName;
    }
    
    /**
     * 
     * @return type
     */
    public function getLicenceKey() {
        return $this->licenceKey;
    }
    
    /**
     * 
     * @return type
     */
    public function getMID() {
        return $this->mid;
    }
    
    /**
     * 
     * @param type $object
     * @return type
     * @throws ClassNotDeserializable
     */
    public function deserialize($object) {
        if (is_object($object)) {
            $array = get_object_vars($object);
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new ClassNotDeserializable();
        }

        $this->accountName = self::getStringOrException($array, "accountName");
        $this->mid = self::getStringOrException($array, "mid");
        $this->licenceKey = self::getStringOrException($array, "licenceKey");
        return $this->this();
    }

    /**
     * 
     * @param type $object
     * @return type
     */
    public function jsonDeserialize($object) {
        return $this->deserialize($object);
    }

    /**
     * 
     * @param type $object
     * @return type
     */
    public function xmlDeserialize($object) {
        return $this->deserialize($object);
    }
}