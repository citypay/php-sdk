<?php
namespace CityPay\Lib;

use CityPay\Encoding\Json\JsonCodec;

/**
 * 
 */
class Configuration
{
    /**
     *
     * @var type 
     */
    private $clientAccount;
    
    /**
     * 
     * @param type $pathName
     * @throws \CityPay\Lib\Exception
     */
    function __construct(
        $pathName
    ) {
        try {
            $this->clientAccount = JsonCodec::initialiseFromFile(
                $pathName,
                Configuration\ClientAccount::class
            );
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $licenceKey
     * @return type
     */
    public function getClientLicenceKey() {
        return $this->clientAccount
            ->getLicenceKey();
    }
    
    /**
     * 
     * @param type $accountName
     * @return type
     */
    public function getMerchantAccount(
        $accountName
    ) {
        return $this->clientAccount
            ->getMerchantAccount($accountName);
    }
}
