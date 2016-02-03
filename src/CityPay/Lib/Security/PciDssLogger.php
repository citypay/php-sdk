<?php
namespace CityPay\Lib\Security;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;

/**
 * 
 */
class PciDssLogger
    extends AbstractLogger
    implements LoggerInterface
{
    /**
     * 
     */
    use \CityPay\Lib\Logging\InterpolationTrait;
    
    /**
     *
     * @var type 
     */
    private $logger;
    
    /**
     * 
     * @param type $logger
     * @throws \InvalidArgumentException
     */
    public function __construct(
        $logger
    ) {
        if (!($logger instanceof LoggerInterface)) {
            throw new \InvalidArgumentException();
        }
        
        $this->logger = $logger;
    }
    
    /**
     * 
     */
    function __destruct() {
        $this->log4php = null;
    }
    
    /**
     * 
     * @param array $array
     */
    private static function generateStringFromArray(
        array $array
    ) {
        $scratch = '[';
        foreach ($array as $key => $value) {
            $scratch .= $key
                .'=';
            if (is_array($value)) {
                $scratch .= self::generateStringFromArray($value);
            } else if (is_object($value)) {
                $scratch .= self::generateStringFromObject($value);
            } else {
                $scratch .= $value;
            }
        }
        $scratch .= ']';
        return $scratch;
    }
    
   /**
    * 
    * @param type $object
    */
   private static function generateStringFromObject(
       $object
   ) {
        if (method_exists($loggableElement, '__toString')) {
            return $loggableElement->__toString();
        } else {
            throw new \CityPay\Lib\UnsupportedOperationException();
        }
   }
    
    /**
     * 
     * @param type $element
     */
    private static function generateStringFromLoggableElement(
        $loggableElement
    ) {
        if (is_array($loggableElement)) {
            return self::generateStringFromArray($loggableElement);
        } else if (is_object($loggableElement)) {
            return self::generateStringFromObject($loggableElement);
        } else {
            throw new \InvalidArgumentException();
        }
    }
    
    /**
     * The log function, in the context of the PciDssLogger is responsible
     * for interpolating the $context into $message and parsing the output
     * to identify data in the $context passed parameter that IS or MIGHT
     * constitute sensitive data under PCI DSS by reference to the
     * $elementTypeMap passed parameter.
     * 
     * @param type $level
     * @param type $message
     * @param array $context
     * @param array $elementTypeMap
     */
    public function log(
        $level,
        $message,
        array $context = array(),
        array $elementTypeMap = array()
    ) {
        //
        // Sanitize the context array by reference to the element type map
        // passed as a parameter.
        //
        $sanitizedContext = PciDss::sanitizeAssociativeArrayElements($context, $elementTypeMap);
        
        //
        // If 'loggable' context is provided and it is an object, or an array,
        // translate the loggable context to a string for straightforward
        // inclusion within the interpolated log message.
        //
        if (array_key_exists('loggable', $sanitizedContext)) {
            $loggable = self::generateStringFromLoggableElement(
                $sanitizedContext['loggable']
            );

            $sanitizedContext['loggable'] = $loggable;
        }
        
        $this->logger->log($level, self::interpolate($message, $sanitizedContext));
        if (!empty($sanitizedContext)) {
            $this->logger->log($level, self::interpolate('{loggable}', $sanitizedContext));
        }
    }
}