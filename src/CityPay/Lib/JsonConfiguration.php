<?php
namespace CityPay\Lib;

use CityPay\Encoding\Json\JsonCodec;

use CityPay\Encoding\Deserializable;
use CityPay\Encoding\Json\JsonDeserializable;
use CityPay\Encoding\Xml\XmlDeserializable;


class JsonConfiguration_inner
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    private $map;
    
    /**
     * 
     * @return \CityPay\Lib\JsonConfiguration
     */
    protected function this() {
        return $this;
    }
    
    /**
     * 
     * @param type $object
     */
    public function deserialize($object) {
        if (is_object($object)) {
            $array = array($object); 
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new \CityPay\Encoding\ClassNotDeserializable();
        }
        
        $table = array();
        foreach ($array as $maplet) {
            $maplet = (new Configuration\Maplet())
                ->deserialize($maplet);
            if (!is_null($maplet)) {
                $this->map[$maplet->getId()] = $maplet;
            }
        }
        
        return $this->this();
    }

    /**
     * 
     * @param type $object
     */
    public function jsonDeserialize($object) {
        return $this->deserialize($object);
    }

    /**
     * 
     * @param type $object
     */
    public function xmlDeserialize($object) {
        return $this->deserialize($object);
    }
    
    /**
     * 
     * @param type $identifier
     */
    public function getOrElse($identifier, $else) {
        if (array_key_exists($identifier, $this->map)) {
            return $this->map[$identifier];
        } else {
            return $else;
        }
    }
}

/**
 * 
 */
class JsonConfiguration
    
{
    /**
     *
     * @var type 
     */
    private $map;
    
    /**
     * 
     * @param type $pathName
     * @throws \CityPay\Lib\Exception
     */
    function __construct(
        $pathName
    ) {
        try {
            $this->map = JsonCodec::initialiseFromFile(
                $pathName,
                JsonConfiguration_inner::class
            );
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $identifier
     */
    public function getOrElse($identifier, $else) {
        return $this->map->getOrElse($identifier, $else);
    }
}
