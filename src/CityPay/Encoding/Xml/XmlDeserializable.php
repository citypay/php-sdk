<?php
namespace CityPay\Encoding\Xml;

use CityPay\Encoding\Deserializable;

/**
 * 
 */
interface XmlDeserializable
    extends Deserializable
{
    /**
     * @param $array
     * @return mixed
     */
    public function xmlDeserialize($object);
}

