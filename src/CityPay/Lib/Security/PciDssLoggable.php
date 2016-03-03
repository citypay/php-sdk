<?php
namespace CityPay\Lib\Security;

/**
 * An interface which, when implemented, enables a class to service requests
 * for 'loggable' data, and metadata relating to 'loggable' data for the
 * purpose of sanitizing for application logging purposes.
 * 
 */
interface PciDssLoggable {
    /**
     * Generates an associative array of potentially sensitive 'loggable' data
     * for the implementing class. The associative array generated is of the
     * form, expressed in JSON notation -
     * 
     *  {
     *      "loggable": {
     *          "<elementName>": <elementValue>,
     *          .
     *          .
     *          .
     *          "<elementName>": <elementValue>
     *      }
     *  }
     * 
     */
    function getPciDssLoggableElementMap();
    
    /**
     * Generates an associative array of the same structure as that generated
     * by the {@link getPciDssLoggableElementType} method indicating, for
     * each element of data, the sensitivity of that data by reference to the
     * PCI DSS standard by reference to the enumeration constants appearing
     * in the {@link PciDss} class.
     * 
     * The associative array generated is of the form, expressed in JSON
     * notation -
     * 
     *  {
     *      "loggable": {
     *          "<elementName>": <elementSensitivityEnumerationConstant>,
     *          .
     *          .
     *          .
     *          "<elementName>": <elementSensitivityEnumerationConstant>
     *      }
     *  }
     * 
     */
    function getPciDssLoggableElementTypeMap();
}