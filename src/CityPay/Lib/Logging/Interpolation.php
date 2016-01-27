<?php
namespace CityPay\Lib\Logging;

trait Interpolation
{
    /**
     * Interpolates context values into the message placeholders.
     * 
     * @return string
     */
    private static function interpolate($message, array $context = array()) {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}

