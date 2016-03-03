<?php
namespace CityPay\Lib;

define('MAPLET_NOT_FOUND_IN_OBJECT', " not found in object.");
define('MAPLET_NOT_FOUND_IN_ARRAY', " not found in array.");

/**
 * 
 */
trait DataAccess
{
    /**
     * 
     * @param type $object
     * @param type $key
     * @param type $default
     * @return type
     * @throws \InvalidArgumentException
     */
    protected function getValueOrDefault($object, $key, $default = null) {
        switch (gettype($object)) {
            case "object":
                if (isset($object->$key)) {
                    return $object->$key;
                } else {
                    return $default;
                }
                break;
            
            case "array":
                if (array_key_exists($key, $object)) {
                    return $object[$key];
                } else {
                    return $default;
                }
                break;
                
            default:
                throw new \InvalidArgumentException();
        }
    }
    
    /**
     * 
     * @param type $object
     * @param type $key
     * @param type $exceptionClass
     * @return type
     * @throws type
     * @throws \InvalidArgumentException
     */
    protected function getValueOrException($object, $key, $exceptionClass = NamedValueNotFoundException::class) {
        assert(is_subclass_of($exceptionClass, 'Exception', true));
        switch (gettype($object)) {
            case "object":
                if (isset($object->$key)) {
                    return $object->$key;
                } else {
                    throw new $exceptionClass(
                        $key . MAPLET_NOT_FOUND_IN_OBJECT
                    );
                }
                break;
            
            case "array":
                if (array_key_exists($key, $object)) {
                    return $object[$key];
                } else {
                    throw new $exceptionClass(
                        $key . MAPLET_NOT_FOUND_IN_ARRAY
                    );
                }
                break;
                
            default:
                throw new \InvalidArgumentException();
        }
    }
    
    /**
     * @param $object
     * @param $key
     * @return bool
     */
    protected function getBooleanOrDefault($object, $key, $default = null) {
        $value = self::getValueOrDefault($object, $key, $default);
        if (strtolower(trim($value)) == "true") {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param $object
     * @param $key
     * @return bool
     */
    protected function getBooleanOrException($object, $key, $exceptionClass = NamedValueNotFoundException::class) {
        $value = self::getValueOrException($object, $key, $exceptionClass);
        if (strtolower(trim($value)) == "true") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $object
     * @param $key
     * @return int
     */
    protected function getIntOrDefault($object, $key, $default = null) {
        $value = self::getValueOrDefault($object, $key, $default);
        if ($value != NULL) {
            return (int) $value;
        } else {
            return NULL;
        }
    }
    
    /**
     * @param $object
     * @param $key
     * @return bool
     */
    protected function getIntOrException($object, $key, $exceptionClass = NamedValueNotFoundException::class) {
        $value = self::getValueOrException($object, $key, $exceptionClass);
        if ($value != NULL) {
            return (int) $value;
        } else {
            return NULL;
        }
    }

    /**
     * @param $object
     * @param $key
     * @return string
     */
    protected function getStringOrDefault($object, $key, $default = null) {
        $value = self::getValueOrDefault($object, $key, $default);
        if ($value != null) {
            return (string) $value;
        } else {
            return NULL;
        }
    }
    
    /**
     * @param $object
     * @param $key
     * @return bool
     */
    protected function getStringOrException($object, $key, $exceptionClass = NamedValueNotFoundException::class) {
        $value = self::getValueOrException($object, $key, $exceptionClass);
        if ($value != null) {
            return (string) $value;
        } else {
            return NULL;
        }
    }
}
