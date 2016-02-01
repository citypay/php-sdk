<?php
namespace \CityPay\Lib\Security;

/**
 * 
 */
trait PciDssLoggableTrait {
    /**
     * 
     * 
     * 
     */
    public function getPciDssLoggableElementMap() {
        $elementMap = array();
        $propertyMap = get_object_vars($this);
        foreach ($propertyMap as $key => $value) {
            $elementMap[$key] = (is_null($value) ? $value : $value->__toString());
        }
        
        return $elementMap;
    }
    
    protected abstract function getPciDssLoggableSensitiveElementTypeMap();
    
    /**
     * 
     */
    public function getPciDssLoggableElementTypeMap() {
        $elementTypeMap = array();
        $propertyMap = get_object_vars($this);
        foreach ($propertyMap as $key => $value) {
            $elementMap[$key] = PciDss::INSENSITIVE;
        }
        
        return array_merge(
            $elementTypeMap,
            self::getPciDssLoggableSensitiveElementTypeMap
        );
    }
}
