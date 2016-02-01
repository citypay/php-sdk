<?php
namespace CityPay\Lib;

use CityPay\Lib\Security\PciDss;
use CityPay\Lib\Security\PciDssLoggable;

/**
 * 
 */
trait PciDssLoggableNameValueComponent
{ 
    /**
     * 
     */
    use  NameValueComponent;
    
        /**
     * 
     * PciDssLoggable interface implementation
     * 
     */
   
    /**
     * {@inheritdoc}
     */
    public function getPciDssLoggableElementMap() {
        $elementMap = array();
        $propertyMap = $this->mapNameValue;
        foreach ($propertyMap as $key => $value) {
            if (!is_null($value)) {
                if (is_object($value)) {
                    if ($value instanceof PciDssLoggable) {
                        $subElementMap = $value->getPciDssLoggableElementMap();
                        $elementMap[$key] = $subElementMap['loggable'];
                    } else {
                        $elementMap[$key] = get_object_vars($value);
                    }
                } else {
                    $elementMap[$key] = (is_null($value) ? 'null' : $value);
                }
            }
        }
     
        return array('loggable' => $elementMap);
    }
    
    /**
     * 
     * @return type
     */
    protected abstract function getPciDssLoggableSensitiveElementTypeMap();
    
    /**
     * 
     * @param type $value
     * @return type
     */
    private function getObjectElementTypeMap($value) {        
        return self::getAssociativeArrayElementTypeMap(
            get_object_vars($value)
        );
    }
   
    /**
     * 
     * @param array $array
     * @return type
     */
    private function getAssociativeArrayElementTypeMap(array $array) {
        $elementTypeMap = array();
        foreach ($array as $key => $value) {
            if (!is_null($value)) {
                if (is_object($value)) {
                    if ($value instanceof PciDssLoggable) {
                        $subElementTypeMap = $value->getPciDssLoggableElementTypeMap();
                        $elementTypeMap[$key] = $subElementTypeMap['loggable'];
                    } else {
                        $elementTypeMap[$key] = self::getObjectElementTypeMap($value);
                    }
                } else if (is_array($value)) {
                    $elementTypeMap[$key] = self::getAssociativeArrayElementTypeMap($value);
                } else {
                    $elementTypeMap[$key] = PciDss::INSENSITIVE;
                }
            }
        }

        return $elementTypeMap;
    }
    
    /**
     * 
     */
    public function getPciDssLoggableElementTypeMap() {
        return array(
            'loggable' => array_merge(
                self::getAssociativeArrayElementTypeMap($this->mapNameValue),
                $this->getPciDssLoggableSensitiveElementTypeMap()
            )
        );
    }
}
