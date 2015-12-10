<?php
namespace CityPay\Encoding\FormUrl;

use CityPay\Encoding\Serializable;

/**
 * 
 */
interface FormUrlSerializable
    extends Serializable
{
    /**
     * Generates an associative array for the relevant interface implementing
     * object.
     * 
     * @return array
     */
    public function formUrlSerialize();
}

