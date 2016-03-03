<?php
namespace CityPay\Encoding\Json;

use CityPay\Encoding\DeserializationException;

/**
 * 
 */
class JsonDeserializationException
    extends DeserializationException
{
    /**
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        if ($message == null) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }
}