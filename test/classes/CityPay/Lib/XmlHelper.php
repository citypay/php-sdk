<?php
namespace CityPay\Lib;

/**
 * 
 */
trait XmlHelper
{
    /**
     * 
     * @param DOMElement $node
     * @return type
     */
    public static function getText($node)
    {
        if ($node instanceof \DOMElement) {
            $textNode = self::getFirstElement($node->childNodes);
            return $textNode->wholeText;
        } else {
            return null;
        }
    }
    
    /**
     * 
     * @param DOMNodeList $nodeList
     * @return type
     */
    public static function getFirstElement($nodeList)
    {
        if ($nodeList instanceof \DOMNodeList) {
            if ($nodeList->length > 0x00) {
                return $nodeList->item(0x00);
            } else {
                return null;
            }
        }        
    }
    
    /**
     * 
     * @param type $node
     * @param type $tag
     * @return type
     */
    public static function getFirstElementByTagName($node, $tag)
    {
        if ($node instanceof \DOMDocument
            || $node instanceof \DOMNode) {
            $nodeList = $node->getElementsByTagName($tag);
            if (!is_null($nodeList)) {
                return self::getFirstElement($nodeList);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
        
