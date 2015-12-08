<?php
namespace CityPay\Encoding;

/**
 * 
 */
class ClassNotDeserializable
    extends \Exception
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