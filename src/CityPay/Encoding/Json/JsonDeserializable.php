<?php
namespace CityPay\Encoding\Json;

use CityPay\Encoding\Deserializable;

/**
 * 
 */
interface JsonDeserializable
    extends Deserializable
{
    /**
     * @param $array
     * @return mixed
     */
    public function jsonDeserialize($object);
}
