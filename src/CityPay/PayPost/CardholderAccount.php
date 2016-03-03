<?php
namespace CityPay\PayPost;

/*use CityPay\Lib\ApiEncoding;
use CityPay\Lib\Rpc\Http;
use CityPay\Lib\NamedValueNotFoundException;
use CityPay\Lib\InvalidGatewayResponseException;*/

trait CardholderAccount
{
    /**
     * 
     */
    abstract function this();
    
    /**
     * 
     * @param type $accountNo
     * @return type
     */
    public function accountNo(
        $accountNo
    ) {
        self::set("accountNo", $accountNo);
        return $this->this();
    }

    public function firstname(
        $firstName
    ) {
        self::set("firstname", $firstName);
        return $this->this();
    }

    public function lastname(
        $lastName
    ) {
        self::set("lastname", $lastName);
        return $this->this();
    }
}