<?php
namespace CityPay\Lib;

/**
 * 
 */
class InvalidGatewayResponseException
    extends \RuntimeException
{
    /**
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this)
        ." '{$this->message}' in {$this->file}({$this->line})\n"
        ."{$this->getTraceAsString()}";
    }
}