<?php
namespace CityPay\Encoding\Json;

use CityPay\Encoding\Serializable;

/**
 * 
 */
interface JsonSerializable
    extends \JsonSerializable,
        Serializable
{
    /**
     * Generates an associative array for the relevant interface implementing
     * object.
     * 
     * @return array
     */
    public function jsonSerialize();
}