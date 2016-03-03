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
        if ($object instanceof FormUrlSerializable) {
            $o = $object->formUrlSerialize();
        } else {
            $o = $object;
        }
        
        return http_build_query($o);
    }

    public static function decode(
        $string
    ) {
        // TODO
    }
}