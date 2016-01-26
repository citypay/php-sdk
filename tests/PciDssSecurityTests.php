<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\Lib\Security\PciDss;

/**
 * 
 */
class PciDssSecurityTest
    extends PHPUnit_Framework_TestCase
{
    use ClientConfiguration;
    
    /**
     * 
     * @param type $s
     */
    public static function generateLuhnCheckDigit($s) {
        return '1';
    }
    
    /**
     *
     */
    public function testSanitizeCardNumber() {
        //
        //  Test a valid 16 digit payment card number, with four digit number
        //  groups separated by whitespace.
        //
        $s1 = PciDss::sanitizeCardNumber('4000 0000 0000 0002');
        $this->assertTrue($s1 === '400000******0002');
        
        //
        //  Test a valid 16 digit payment card number.
        //
        $s2 = PciDss::sanitizeCardNumber('4000000000000002');
        $this->assertTrue($s2 === '400000******0002');
        
        //
        //  Test a valid 19 digit payment card number
        //
        $s3 = PciDss::sanitizeCardNumber('6759380000000000005');
        $this->assertTrue($s3 === '675938*********0005');
        
        //
        //  Test a valid 15 digit payment card number
        //
        $s4 = PciDss::sanitizeCardNumber('374387188019714');
        $this->assertTrue($s4 === '374387******714');
        
        //
        //  Test a valid 14 digit payment card number.
        //        
        $s5 = PciDss::sanitizeCardNumber("30144453965469");
        $this->assertTrue($s5 === '301444******69');
        
        //
        //  Test a valid payment card number with 12 digits (generated from an
        //  11 digit number)
        //
        $p = '30144453965'
                .static::generateLuhnCheckDigit('30144453965');
        $p_max = strlen($p);
        $p2 = substr($p, 0, 6)
            .str_repeat('*', $p_max - 6);
        $s6 = PciDss::sanitizeCardNumber($p);
        $this->assertTrue($s6 === $p2);
        
        //
        //  Test a valid payment card number with 11 digits (generated from an
        //  10 digit number)
        //
        $p = '3014445396'
                .static::generateLuhnCheckDigit('3014445396');
        $p_max = strlen($p);
        $p2 = str_repeat('n', $p_max - 4)
            .substr($p, $p_max - 4, 4);
        $s7 = PciDss::sanitizeCardNumber($p);
        $this->assertTrue($s7 === $p2);
        
        //
        //  Test an invalid payment card number.
        //
        $s5 = PciDss::sanitizeCardNumber("40000A0000000002");
        $this->assertTrue($s5 === "nnnnnannnnnn0002");
    }
    
    /**
     * 
     */
    public function testSanitizeCsc() {
        $s = PciDss::sanitizeCsc("123");
        $this->assertTrue($s === "***");
        
        $s = PciDss::sanitizeCsc("12");
        $this->assertTrue($s === "***");
        
        $s = PciDss::sanitizeCsc("1");
        $this->assertTrue($s === "***");
        
        $s = PciDss::sanitizeCsc("1234");
        $this->assertTrue($s === "***");
    }
    
    /**
     * 
     */
    public function testSanitizeAssociativeArrayElements() {
        
    }
}
