<?php
namespace CityPay\Lib\Configuration;

use CityPay\Encoding\Deserializable;
use CityPay\Encoding\Json\JsonDeserializable;
use CityPay\Encoding\Xml\XmlDeserializable;

class Maplet
    implements Deserializable,
        JsonDeserializable,
        XmlDeserializable
{
    use \CityPay\Lib\DataAccess;
    
    /**
     *
     * @var type 
     */
    private $identifier;
    
    /**
     *
     * @var type 
     */
    private $type;
    
    /**
     *
     * @var type 
     */
    private $data;
    
    /**
     * 
     * @return \CityPay\Lib\Configuration\Maplet
     */
    protected function this() {
        return $this;
    }
    
    /**
     * 
     * @param type $data
     * @param type $format
     */
    private function deserializeString($data, $format) {     
        switch ($format) {
            case "multi-line":
                if (is_string($data)) {
                    return $data;
                } else if (is_array($data)) {
                    return implode($data);
                } else {
                    return (string) $data;
                }
                break;
            
            default:
                if (is_string($data)) {
                    return $data;
                } else {
                    return (string) $data;
                }
                break;
        }
    }
    
    /**
     * 
     * @param type $object
     */
    public function deserialize($object) {
        if (is_object($object)) {
            $array = get_object_vars($object);
        } else if (is_array($object)) {
            $array = $object;
        } else {
            throw new \CityPay\Encoding\ClassNotDeserializable();
        }
        
        $this->identifier = self::getStringOrException($array, "identifier");
        $this->type = self::getStringOrException($array, "type");
        
        switch ($this->type) {
            case "string":
                $format = self::getStringOrException($array, "format");
                $this->data = $this->deserializeString(
                    self::getValueOrException($array, "data"),
                    $format
                );
                break;

        }
        
        return $this->this();
    }
    
    /**
     * 
     * @param type $object
     */
    public function jsonDeserialize($object) {
        $this->deserialize($object);
    }

    /**
     * 
     * @param type $object
     */
    public function xmlDeserialize($object) {
        $this->deserialize($object);
    }
    
    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->identifier;
    }
    
    /**
     * 
     * @return type
     */
    public function getData() {
        return $this->data;
    }
}


