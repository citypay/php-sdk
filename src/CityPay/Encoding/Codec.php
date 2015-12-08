<?php
namespace CityPay\Encoding;

use CityPay\Lib\UnsupportedOperationException;
use CityPay\Lib\FileSystemException;
use CityPay\Lib\FileNotFoundException;
use CityPay\Encoding\Deserializable;

define('DESERIALIZABLE_INTERFACE', 'CityPay\Encoding\Deserializable');

/**
 * 
 */
class Codec
{
    /**
     * @param $object
     * @return mixed
     */
    public static function encode(
        $object
    ) {
        throw new UnsupportedOperationException();
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function decode(
        $string
    ) {
        throw new UnsupportedOperationException();
    }
    
    public static function decodeFile(
        $pathName
    ) {
        if (!file_exists($pathName)) {
            throw new FileNotFoundException();
        }
        
        $len = filesize($pathName);
        if ($len > 0x00) {
            $f = fopen($pathName, "r");
            if (!$f) {
                throw new FileSystemException();
            }
            
            $data = fread($f, $len);
            fclose($f);
        } else {
            $data = "";
        }
       
        return static::decode($data);
    }
    
    /**
     * 
     * @param type $object
     * @param type $class
     * @param type $deserializationExceptionClass
     * @return type
     * @throws ClassNotDeserializable
     * @throws DeserializationException
     */
    protected static function _initialiseFrom(
        $object,
        $class,
        $deserializationExceptionClass = null
    ) {
        if ($deserializationExceptionClass == null) {
            throw new InvalidArgumentException();
        }
        
        if (!($object instanceof \stdClass)) {
            throw new DeserializationException();
        }
        
        $output = new $class;
        if (!($output instanceof Deserializable)) {
            throw new ClassNotDeserializable();
        }

        return $output->deserialize($object);
    }
    
    /**
     * 
     * @param type $object
     * @param type $class
     * @return type
     */
    public static function initialiseFrom(
        $object,
        $class
    ) {
        return self::_initialiseFrom(
            $object,
            $class,
            DeserializationException::class  
        );
    }
    
    /**
     * 
     * @param type $pathName
     * @param type $class
     * @return type
     */
    public static function initialiseFromFile(
        $pathName,
        $class
    ) {
        return self::_initialiseFrom(
            self::decodeFile($pathName),
            $class,
            DeserializationException::class
        );
    }
}