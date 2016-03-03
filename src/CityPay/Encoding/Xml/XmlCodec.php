<?php
namespace CityPay\Encoding\Xml;

use CityPay\Encoding\Codec;
use CityPay\Encoding\ClassNotDeserializable;
use CityPay\Encoding\XmlDeserializableException;

define('XML_DESERIALIZABLE_INTERFACE', 'CityPay\Encoding\Xml\XmlDeserializable');

/**
 * 
 */
class XmlCodec
    extends Codec
{
    public static function encode(
        $object
    ) {
        // TODO: Implement encode() method.
    }

    public static function decode(
        $string
    ) {
        return simplexml_load_string($string);
        /*if ($object != null) {
            return (array) $object;
        }
        
        return Array();*/
    }
    
    /**
     * @param $string
     * @param $class
     * @return mixed
     * @throws JsonDeserializableException
     */
    public static function initialiseFrom(
        $object,
        $class
    ) {
        if (!in_array(XML_DESERIALIZABLE_INTERFACE, class_implements($class))) {
            throw new ClassNotDeserializable();
        }

        $object = self::decode($string);
        if (!$object instanceof \stdClass) {
            throw new XmlDeserializableException();
        }

        $y = new $class;
        if (!$y instanceof JsonDeserializable) {
            throw new ClassNotDeserializable();
        }

        return $y->xmlDeserialize($object);
    }
}