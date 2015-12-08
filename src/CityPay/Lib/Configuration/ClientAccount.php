<?php
namespace CityPay\Lib\Configuration;

use CityPay\Encoding\Deserializable;
use CityPay\Encoding\Json\JsonDeserializable;
use CityPay\Encoding\Xml\XmlDeserializable;

/**
 * 
 */
class ClientAccount
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    use \CityPay\Lib\DataAccess;
     
    /**
     * 
     */
    private $clientId;

    /**
     * 
     */
    private $accountName;

    /**
     * 
     */
    private $licenceKey;
     
    /**
     * @var array
     */
    private $merchantAccounts;
    
    /**
     * 
     * @return \CityPay\Lib\Configuration\ClientAccount
     */
    protected function this() {
        return $this;
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
     * @param type $accountName
     * @return type
     */
    public function getMerchantAccount(
        $accountName
    ) {
        return $this->merchantAccounts[$accountName];
    }
    
    /**
     * 
     * @param type $object
     */
    public function deserialize($object) {
        if (is_object($object)) {
            $array = get_object_vars($object);
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new \CityPay\Encoding\ClassNotDeserializable();
        }

        $this->clientId = self::getStringOrException($array, "clientId");
        $this->accountName = self::getStringOrException($array, "accountName");
        $this->licenceKey = self::getStringOrException($array, "licenceKey");
        
        $merchantAccounts = self::getValueOrException($array, "merchantAccounts");
        $this->merchantAccounts = array();
        foreach ($merchantAccounts as $merchantAccount) {
            $merchantAccountObject = (new MerchantAccount())
                ->deserialize($merchantAccount);
            if ($merchantAccountObject != null) {
                $this->merchantAccounts[$merchantAccountObject->getAccountName()] = $merchantAccountObject;
            }
        }
        
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
