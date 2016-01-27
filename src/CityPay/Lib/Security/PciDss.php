<?php
namespace CityPay\Lib\Security;

class PciDss {
    
    const INSENSITIVE = 0x00;
    const CARDNUMBER = 0x01;
    const CSC = 0x02;
    
    /**
     * 
     * @param type $s
     */
    public static function luhnCheck($s) {
        return true;
    }
    
    /**
     * 
     * @param string $message
     * @return string
     */
    public static function sanitizeAssociativeArrayElements(
        array $array,
        array $elementTypeMap
    ) {
        $output = array();
        foreach($array as $key => $value) {
            if (array_key_exists($key, $elementTypeMap)) {
                $elementType = $elementTypeMap[$key];
                switch ($elementType) {
                    case static::INSENSITIVE:
                        break;

                    case static::CARDNUMBER:
                        $output[$key] = static::sanitizeCardNumber($value);
                        break;

                    case static::CSC:
                        $output[$key] = static::sanitizeCsc($value);
                        break;

                    default:
                        break;
                }
            } else {
                $output[$key] = $value;
            }
        }
        
        return $output;
    }
    
    /**
     * Generates a sanitized version of a recognizable payment card number.
     *
     * A payment card number comprises two parts: an issuer / bank
     * identification number ("IIN" or "BIN"), and a primary account
     * number ("PAN"). IIN / BIN numbers are allocated in accordance
     * with ISO/IEC 7812.
     * 
     * The IIN/BIN is 6 digits long; where an ISO/IEC 7812 compliant
     * payment card number may vary in total length from between 12 digits
     * and 19 digits.
     * 
     * A new standard for IIN/BIN numbers is under development to
     * increase the length of the IIN/BIN from 6 digits to 8 digits.
     * Accordingly, whereas the algorithm implemented herein supports
     * number groups of between 12 and 19 digits; expansion to support
     * number groups of upto 21 digits will need to be introduced.
     * 
     * The approach adopted for the sanitize process is as follows -
     * 
     * (1)  If $value is a valid LUHN checked payment card number or
     *      the payment card number falls between 12 and 21 digits in
     *      length, output the first six digits, a variable number of
     *      asterisks and the last four digits.
     * 
     * (2)  If $value is not a valid LUHN checked payment card number,
     *      output a variable number of minus signs, followed by the last
     *      four digits.
     * 
     * In both cases, the variable number of asterisks generated are to
     * be sufficient to pad the payment card number to an equal number of
     * digits as provided by $value.
     * 
     * @param string $value
     * @return string
     */
    public static function sanitizeCardNumber($value) {
        //
        //  1.  Normalise $value, by removing all whitespace.
        //
        $i_max = strlen($value);
        $i = 0x00;
        $s = '';
        while ($i < $i_max) {
            $c = $value[$i++];
            if (ctype_digit($c)) {
                $s .= $c;
            } else if (ctype_alpha($c)) {       
                $s .= 'a';
            } else if (ctype_space($c)) {
                
            } else {
                $s .= 'x';
            }
        }
        
        //
        //  2.  If the resultant value consists entirely of numeric digits,
        //      perform LUHN check, and generate the appropriate santized
        //      version.
        //      
        //      If the value does not consist of numeric digits, render
        //      a representative value comprising a set of numeric digits
        //      replaced by 'n', alpha digits replaced by 'a', other
        //      digits replaced by 'x'
        //      
        //
        $j_max = strlen($s);
        if (ctype_digit($s)) {
            //
            //  (A) The common case, where $s is comprised entirely of
            //      numeric digits.
            //
            if ($j_max >= 16 && $j_max <= 19 && static::luhnCheck($s)) {
                //
                //  (a) The common case, with valid payment card numbers
                //      of length between 16 and 19 digits inclusive;
                //      for example: VISA, and MasterCard.
                //
                $o = substr($s, 0, 6)
                    .str_repeat('*', $j_max - 10)
                    .substr($s, $j_max - 4, 4);
            } else if ($j_max > 12 && $j_max < 16 && static::luhnCheck($s)) {
                //
                //  (b) The lesser common case, with valid payment card
                //      numbers of length between 13 and 15 digits
                //      inclusive; for example: American Express,.
                //
                $o = substr($s, 0, 6)
                    .str_repeat('*', 6)
                    .substr($s, $j_max - ($j_max - 12));
            } else if ($j_max > 19 && static::luhnCheck($s)) {
                //
                //  (c) The case where a value with more than 19 digits
                //      is provided, and it is valid according to the
                //      LUHN check algorithm.
                //
                $o = substr($s, 0, 5)
                    .str_repeat('*', $j_max - 10)
                    .substr($s, $j_max - 4, 4);
            } else if ($j_max == 12 && static::luhnCheck($s)) {
                //
                //  (d) The case where a value with 12 digits
                //      is provided, or where the value is valid
                //      according to the LUHN check algorithm.
                //
                $o = substr($s, 0, 6)
                    .str_repeat('*', $j_max - 6);
            } else if ($j_max < 12 || !static::luhnCheck($s)) {
                //
                //  (e) The case where a value with 12 or less digits
                //      is provided,
                //      
                //      OR
                //      
                //      where the value is invalid according to the LUHN
                //      check algorithm.
                //
                $o = str_repeat('n', $j_max - 4)
                    .substr($s, $j_max - 4, 4);
            }
        } else {
            //
            //  (b) The case where $s comprises a mixture of numeric and
            //      alpha characters, and is therefore invalid. 
            //
            $o = preg_replace('([0-9])', 'n', substr($s, 0, $j_max - 4))
                .substr($s, $j_max - 4, 4);
        }
        
        return $o;
    }
    
    /**
     * Generates a sanitized version of a recognizable card security number.
     * 
     * @param string $value
     * @return string
     */
    public static function sanitizeCsc($value) {
        $i_max = strlen($value);
        if ($i_max > 0x00 && ctype_digit($value)) {
            return str_repeat('*', $i_max);
        } else {
            $o = preg_replace('([A-Za-z])', 'a', $value);
            $o = preg_replace('([0-9])', 'n', $o);
            $o = preg_replace('([^0-9A-Za-z])', 'x', $o);
            return $o;
        }
    }
}
