<?php
namespace CityPay\Encoding\FormUrl;

use CityPay\Encoding\Codec;

/**
 * 
 */
class FormUrlCodec
    extends Codec
{
    public static function encode(
        $object
    ) {
        $x = http_build_query($object);
        return $x;
    }

    public static function decode(
        $string
    ) {
        // TODO
    }
}