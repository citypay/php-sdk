<?php
namespace CityPay\PayLink;

/**
 * The Cardholder is designed to contain and represent the attributes
 * associated with a cardholder in the context of a PayLink transaction
 * request.
 */
class Cardholder
    implements \CityPay\Lib\Security\PciDssLoggable
{
    use \CityPay\Lib\PciDssLoggableNameValueComponent;
    
    /**
     *
     */
    function __construct() { }

    /**
     * @param string $acceptHeaders
     * @return \CityPay\PayLink\Cardholder
     */
    public function acceptHeaders(
        $acceptHeaders
    ) {
        selt::set("acceptHeaders", $acceptHeaders);
        return $this;
    }

    /**
     * @param \CityPay\PayLink\Address $address
     * @return \CityPay\PayLink\Cardholder
     */
    public function address(
        $address
    ) {
        self::set("address", $address);
        return $this;
    }

    /**
     * @param string $email
     * @return \CityPay\PayLink\Cardholder
     */
    public function email(
        $email
    ) {
        self::set("email", $email);
        return $this;
    }

    /**
     * @param string $firstName
     * @return \CityPay\PayLink\Cardholder
     */
    public function firstName(
        $firstName
    ) {
        self::set("firstName", $firstName);
        return $this;
    }

    /**
     * @param string $lastName
     * @return \CityPay\PayLink\Cardholder
     */
    public function lastName(
        $lastName
    ) {
        self::set("lastName", $lastName);
        return $this;
    }

    /**
     * @param string $title
     * @return \CityPay\PayLink\Cardholder
     */
    public function title(
        $title
    ) {
        self::set("title", $title);
        return $this;
    }

    /**
     * @param string $remoteAddress
     * @return \CityPay\PayLink\Cardholder
     */
    public function remoteAddress(
        $remoteAddress
    ) {
        self::set("remoteAddr", $remoteAddress);
        return $this;
    }

    /**
     * @param string $userAgent
     * @return \CityPay\PayLink\Cardholder
     */
    public function userAgent(
        $userAgent
    ) {
        self::set("userAgent", $userAgent);
        return $this;
    }
    
    /**
     * 
     * 
     */
    protected function getPciDssLoggableSensitiveElementTypeMap() {
        return array();
    }
}