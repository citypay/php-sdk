<?php

namespace CityPay\Encoding\Json;

use CityPay\Encoding\Codec;
use CityPay\Encoding\ClassNotDeserializable;
use CityPay\Encoding\Json\JsonDeserializationException;

define('JSON_DESERIALIZABLE_INTERFACE', 'CityPay\Encoding\Json\JsonDeserializable');

/**
 *
 */
class JsonCodec
    extends Codec
{
    /**
     * @param $object
     * @return string
     */
    public static function encode($object)
    {
        if (is_object($object) && method_exists($object, 'toJson')) {
            return $object->toJson();
        } else {
            return json_encode($object, JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function decode($string)
    {
        return json_decode($string);
    }

    /**
     *
     * @param \stdClass $object
     * @param type $class
     * @return type
     * @throws ClassNotDeserializable
     * @throws JsonDeserializationException
     */
    private static function initialiseFromObject($object, $class)
    {
        if (!in_array(JSON_DESERIALIZABLE_INTERFACE, class_implements($class))) {
            throw new ClassNotDeserializable();
        }

        if (!$object instanceof \stdClass) {
            throw new JsonDeserializationException();
        }

        $y = new $class;
        if (!$y instanceof JsonDeserializable) {
            throw new ClassNotDeserializable();
        }

        return $y->jsonDeserialize($object);
    }

    /**
     * @param $string
     * @param $class
     * @return mixed
     * @throws JsonDeserializableException
     */
    public static function initialiseFrom($object, $class)
    {
        switch (gettype($object)) {
            case "object":
                $sourceObject = $object;
                break;

            case "string":
                $sourceObject = self::decode($object);
                break;

            default:
                throw new \InvalidArgumentException();
        }

        if ($sourceObject instanceof \stdClass) {
            return self::initialiseFromObject($sourceObject, $class);
        } else {
            throw new JsonDeserializationException(
                "initialiseFrom: \$sourceObject not set."
            );
        }
    }
}