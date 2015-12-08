<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\Lib\NamedValueNotFoundException;
use CityPay\Lib\InvalidGatewayResponseException;
use CityPay\Encoding\Json\JsonCodec;
use CityPay\PayLink\PayLinkPostbackNotice;

/**
 * 
 */
class PayLinkPostbackNoticeTest
    extends PHPUnit_Framework_TestCase
{
    use ClientConfiguration;
    
    /**
     * 
     * @return type
     */
    private static function postbackResponse_success() {
        return '{"sha1":"NH9cpOkox8Vywlks2sQDF1k9f8o=",'
            .'"cardScheme":"","expYear":"0","CSCResponse":" ",'
            .'"digest":"oDdgUIbRE5a9WTkRHL1SoQ==","email":"noreply@citypay.com",'
            .'"identifier":"CityPay-SDK-PHP-PostbackResponse","firstname":"Howard",'
            .'"AVSResponse":" ","postcode":"JE2 3RL","result":"19",'
            .'"errormessage":"Transactioncancelled","country":"GB",'
            .'"amount":"5001","sha256":"fDnFSn0i4KBrfdWBNJs4WHOzAhKys1E/o/Gfkakax64=",'
            .'"maskedPan":"","lastname":"Hughes","expMonth":"0","cac":"",'
            .'"status":"C","errorid":"080","currency":"GBP",'
            .'"address":"16 Dumaresq Street, St Helier, Jersey",'
            .'"errorcode":"080","mode":"test","authcode":"","cardSchemeId":"0",'
            .'"title":"","authorised":"false","cac_id":"",'
            .'"transno":"0","merchantid":"12345678"}';
    }

    /**
     * 
     * @return type
     */
    public static function postbackResponse_packet_structure_failure() {
        return '{"sha1":"JyK4/uVJcDQ3ZK5ao936H/f4Bng=",'
            .'"cardScheme":"","expYear":"0","CSCResponse":" ",'
            .'"digest":"NQ9ULRuxge2Rqacd7eUDKQ==","email":"noreply@citypay.com",'
            .'"identifier":"CityPay-SDK-PHP-PostbackResponse","firstname":"Howard",'
            .'"AVSResponse":" ","postcode":"JE2 3RL","result":"19",'
            .'"errormessage":"Transactioncancelled","country":"GB",'
            .'"amount":"5001","sha256":"0W01AdnCS1HzqQ+BJ8vC2QPDc6AfP+O+EC6mAstKjEw=",'
            .'"maskedPan":"","lastname":"Hughes","expMonth":"0","cac":"",'
            .'"status":"C","errorid":"080","currency":"GBP",'
            .'"address":"16 Dumaresq Street, St Helier, Jersey",'
            .'"errorcode":"080","mode":"test","authcode":"","cardSchemeId":"0",'
            .'"title":"","authorised":"false","cac_id":"",'
            .'"transno":"0"}'; //"merchantid":"12345678"}';
    }
    
    /**
     * 
     * @return type
     */
    public static function postbackResponse_packet_digest_failure() {
        return '{"sha1":"NH9cpOkox8Vywlks2sQDF1k9f8o=",'
            .'"cardScheme":"","expYear":"0","CSCResponse":" ",'
            .'"digest":"oDdgUIbRE5a9WTkRHL1SoQ==","email":"noreply@citypay.com",'
            .'"identifier":"CityPay-SDK-PHP-PostbackResponse","firstname":"Howard",'
            .'"AVSResponse":" ","postcode":"JE2 3RL","result":"19",'
            .'"errormessage":"Transactioncancelled","country":"GB",'
            .'"amount":"5001","sha256":"0W01AdnCS1HzqQ+BJ8vC2QPDc6AfP+O+EC6mAstKjEw=",'
            .'"maskedPan":"","lastname":"Hughes","expMonth":"0","cac":"",'
            .'"status":"C","errorid":"080","currency":"GBP",'
            .'"address":"16 Dumaresq Street, St Helier, Jersey",'
            .'"errorcode":"080","mode":"test","authcode":"","cardSchemeId":"0",'
            .'"title":"","authorised":"false","cac_id":"",'
            .'"transno":"0","merchantid":"12345678"}';
    }

    /**
     *
     */
    public function testPayLinkPostbackNotice_packet_structure_failure()
    {
        try {
            $postbackNotice = JsonCodec::initialiseFrom(
                    PayLinkPostbackNoticeTest::postbackResponse_packet_structure_failure(),
                    PayLinkPostbackNotice::class
                )
                ->licenceKey("MTD25HDPUVVBBG23")
                ->validate();
            
            $this->assertFalse(
                ($postbackNotice instanceof PayLinkPostbackNotice),
                "JSON packet should not be of type CityPay\PayLink\PayLinkPostbackNotice ("
                    .get_class($postbackNotice)
                    .")"
            );
        } catch (Exception $e) {
            $this->assertFalse(
                ($e instanceof \LogicException),
                "Exception is a 'LogicException' ("
                    .get_class($e)
                    .": "
                    .$e->getMessage()
                    .")"
            );
            
            $this->assertTrue(
                ($e instanceof \RuntimeException),
                "Exception is not a subclass of 'RuntimeException' ("
                    .get_class($e)
                    .")."
            );
            
            $this->assertTrue(
                ($e instanceof NamedValueNotFoundException),
                "Exception is not NamedValueNotFoundException"
            );
            
            $this->assertContains(
                "merchantid",
                $e->getMessage(),
                "NamedValueNotFoundException: does not refer to merchantid"
            );
        }
    }
    
    /**
     *
     */
    public function testPayLinkPostbackNotice_packet_digest_failure()
    {
        try {
            $postbackNotice = JsonCodec::initialiseFrom(
                    PayLinkPostbackNoticeTest::postbackResponse_packet_digest_failure(),
                    PayLinkPostbackNotice::class
                )
                ->licenceKey("MTD25HDPUVVBBG23")
                ->validate();
            
            $this->assertFalse(
                ($postbackNotice instanceof PayLinkPostbackNotice),
                "JSON packet should not be of type CityPay\PayLink\PayLinkPostbackNotice ("
                    .get_class($postbackNotice)
                    .")"
            );
        } catch (Exception $e) {
            $this->assertFalse(
                ($e instanceof \LogicException),
                "Exception is a 'LogicException' ("
                    .get_class($e)
                    .": "
                    .$e->getMessage()
                    .")"
            );
            
            $this->assertTrue(
                ($e instanceof \RuntimeException),
                "Exception is not a subclass of 'RuntimeException' ("
                    .get_class($e)
                    .")."
            );
            
            $this->assertTrue(
                ($e instanceof InvalidGatewayResponseException),
                "Exception is not an InvalidGatewayResponseException ("
                    .get_class($e)
                    .")"
            );
            
            $this->assertContains(
                "sha256 | 0W01AdnCS1HzqQ+BJ8vC2QPDc6AfP+O+EC6mAstKjEw= cf. fDnFSn0i4KBrfdWBNJs4WHOzAhKys1E/o/Gfkakax64=",
                $e->getMessage()
            );
        }
    }
    
    /**
     *
     */
    public function testPayLinkPostbackNotice_success()
    {
        try {
            $postbackNotice = JsonCodec::initialiseFrom(
                    PayLinkPostbackNoticeTest::postbackResponse_success(),
                    PayLinkPostbackNotice::class
                )
                ->licenceKey("MTD25HDPUVVBBG23")
                ->validate();

            $this->assertTrue(
                ($postbackNotice instanceof PayLinkPostbackNotice),
                "JSON packet is not of type CityPay\PayLink\PayLinkPostbackNotice ("
                    .get_class($postbackNotice)
                    .")"
            );
        } catch (Exception $e) {
            throw $e;
            $this->assertTrue(
                ($e instanceof \RuntimeException),
                "Exception is not a subclass of 'RuntimeException' ("
                    .get_class($e)
                    .")."
            );
        }
    }
}