<?php
namespace CityPay\Encoding;

/**
 * 
 */
interface Deserializable {
    /**
     * @param $array
     * @return mixed
     */
    public function deserialize($object);
}