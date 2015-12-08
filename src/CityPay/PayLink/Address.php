<?php
namespace CityPay\PayLink;

/**
 * 
 */
class Address
{
    use \CityPay\Lib\NameValueComponent;
    
    /**
     *
     */
    function __construct() { }

    /**
     * Initialise line 1 of the address.
     * 
     * @param string $address1
     * @return \CityPay\PayLink\Address
     */
    public function address1(
        $address1
    ) {
        self::set("address1", $address1);
        return $this;
    }

    /**
     * Initialise line 2 of the address.
     * 
     * @param string $address2
     * @return \CityPay\PayLink\Address
     */
    public function address2(
        $address2
    ) {
        self::set("address2", $address2);
        return $this;
    }

    /**
     * Initialise the area of the address.
     * 
     * @param string $area
     * @return \CityPay\PayLink\Address
     */
    public function area(
        $area
    ) {
        self::set("area", $area);
        return $this;
    }

    /**
     * Initialise the country of the address.
     * 
     * @param string $country
     * @return \CityPay\PayLink\Address
     */
    public function country(
        $country
    ) {
        self::set("country", $country);
        return $this;
    }

    /**
     * Initialise the postcode of the address.
     * 
     * @param string $postcode
     * @return \CityPay\PayLink\Address
     */
    public function postcode(
        $postcode
    ) {
        self::set("postcode", $postcode);
        return $this;
    }
}