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
     * The log function, in the context of the PciDssLogger is responsible
     * for interpolating the $context into $message and parsing the output
     * to identify IS or MIGHT constitute sensitive data under PCI DSS.
     * 
     * @param type $level
     * @param type $message
     * @param array $context
     */
    public function log(
        $level,
        $message,
        array $context = array(),
        array $elementTypeMap = array()
    ) {
        $sanitizedContext = PciDss::sanitizeAssociativeArrayElements($context, $elementTypeMap);
        $this->logger->log($level, $message, $sanitizedContext);
    }
}