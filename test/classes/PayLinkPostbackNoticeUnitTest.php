<?php
require(__DIR__ . '/../../vendor/autoload.php');

use CityPay\Lib\NamedValueNotFoundException;
use CityPay\Lib\InvalidGatewayResponseException;
use CityPay\Encoding\Json\JsonCodec;
use CityPay\PayLink\PayLinkPostbackNotice;

/**
 *
 */
class PayLinkPostbackNoticeUnitTest
    extends PHPUnit_Framework_TestCase
{
    use \CityPay\Lib\ClientConfiguration {
        \CityPay\Lib\ClientConfiguration::initTrait as initClientConfigurationTrait;
    }

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::initClientConfigurationTrait();
    }

    /**
     *
     * @return string
     */
    private static function postbackResponse_success()
    {
        $str = <<<EOD
{    
    "sha1": "L66ss5eQuBbDuUBPS7j5TQP7HDo=",
    "cardScheme": "Visa Debit",
    "expYear": 2018,
    "authenticationResult": "Y",
    "CSCResponse": "M",
    "digest": "C5s33819SkMqk0F2ZCDlOQ==",
    "email": "support@citypay.com",
    "identifier": "ExampleIdent",
    "firstname": "Jo",
    "AVSResponse": "M",
    "cavv": "AAABBTR2UCRUOZQCR3ZQAAAAAAA=",
    "postcode": "L12345",
    "result": 1,
    "datetime": "2017-08-31T00:02:44.742Z",
    "_paymentRedirect": "redirectionUri",
    "errormessage": "Accepted Transaction",
    "country": "GB",
    "amount": 2000,
    "sha256": "CP+2/luf2HfMmYRjqABP3j/xRoJ0fRjQisuSYIhB31k=",
    "maskedPan": "400000******0004",
    "lastname": "Bloggs",
    "expMonth": 7,
    "cac": 0,
    "status": "O",
    "errorid": "000",
    "currency": "GBP",
    "address": "Somewhere, Over the Rainbow",
    "errorcode": "000",
    "mode": "live",
    "authcode": "383129",
    "cardSchemeId": "VD",
    "eci": "5",
    "title": "",
    "authorised": "true",
    "cac_id": "",
    "transno": 14152,
    "merchantid": 123456
}
EOD;

        return $str;


    }

    /**
     *
     * @return string
     */
    public static function postbackResponse_packet_structure_failure()
    {
        return '{"sha1":"JyK4/uVJcDQ3ZK5ao936H/f4Bng=",'
            . '"cardScheme":"","expYear":"0","CSCResponse":" ",'
            . '"digest":"NQ9ULRuxge2Rqacd7eUDKQ==","email":"noreply@citypay.com",'
            . '"identifier":"CityPay-SDK-PHP-PostbackResponse","firstname":"Howard",'
            . '"AVSResponse":" ","postcode":"JE2 3RL","result":"19",'
            . '"errormessage":"Transactioncancelled","country":"GB",'
            . '"amount":"5001","sha256":"0W01AdnCS1HzqQ+BJ8vC2QPDc6AfP+O+EC6mAstKjEw=",'
            . '"maskedPan":"","lastname":"Hughes","expMonth":"0","cac":"",'
            . '"status":"C","errorid":"080","currency":"GBP",'
            . '"address":"16 Dumaresq Street, St Helier, Jersey",'
            . '"errorcode":"080","mode":"test","authcode":"","cardSchemeId":"0",'
            . '"title":"","authorised":"false","cac_id":"",'
            . '"transno":"0"}'; //"merchantid":"12345678"}';
    }

    /**
     *
     * @return string
     */
    public static function postbackResponse_packet_digest_failure()
    {
        return '{"sha1":"NH9cpOkox8Vywlks2sQDF1k9f8o=",'
            . '"cardScheme":"","expYear":"0","CSCResponse":" ",'
            . '"digest":"oDdgUIbRE5a9WTkRHL1SoQ==","email":"noreply@citypay.com",'
            . '"identifier":"CityPay-SDK-PHP-PostbackResponse","firstname":"Howard",'
            . '"AVSResponse":" ","postcode":"JE2 3RL","result":"19",'
            . '"errormessage":"Transactioncancelled","country":"GB",'
            . '"amount":"5001","sha256":"0W01AdnCS1HzqQ+BJ8vC2QPDc6AfP+O+EC6mAstKjEw=",'
            . '"maskedPan":"","lastname":"Hughes","expMonth":"0","cac":"",'
            . '"status":"C","errorid":"080","currency":"GBP",'
            . '"address":"16 Dumaresq Street, St Helier, Jersey",'
            . '"errorcode":"080","mode":"test","authcode":"","cardSchemeId":"0",'
            . '"title":"","authorised":"false","cac_id":"",'
            . '"transno":"0","merchantid":"12345678"}';
    }

    /**
     *
     */
    public function testPayLinkPostbackNotice_packet_structure_failure()
    {
        try {
            $postbackNotice = JsonCodec::initialiseFrom(
                PayLinkPostbackNoticeUnitTest::postbackResponse_packet_structure_failure(),
                PayLinkPostbackNotice::class
            )
                ->licenceKey("MTD25HDPUVVBBG23")
                ->validate();

            $this->assertFalse(
                ($postbackNotice instanceof PayLinkPostbackNotice),
                "JSON packet should not be of type CityPay\PayLink\PayLinkPostbackNotice ("
                . get_class($postbackNotice)
                . ")"
            );
        } catch (Exception $e) {
            $this->assertFalse(
                ($e instanceof \LogicException),
                "Exception is a 'LogicException' ("
                . get_class($e)
                . ": "
                . $e->getMessage()
                . ")"
            );

            echo $e;

//            $this->assertTrue(
//                ($e instanceof \RuntimeException),
//                "Exception is not a subclass of 'RuntimeException' ("
//                    .get_class($e)
//                    .")."
//            );

//            $this->assertTrue(
//                ($e instanceof NamedValueNotFoundException),
//                "Exception is not NamedValueNotFoundException"
//            );
//
//            $this->assertContains(
//                "merchantid",
//                $e->getMessage(),
//                "NamedValueNotFoundException: does not refer to merchantid"
//            );
        }
    }

    /**
     *
     */
    public function testPayLinkPostbackNotice_packet_digest_failure()
    {
        try {
            $postbackNotice = JsonCodec::initialiseFrom(
                PayLinkPostbackNoticeUnitTest::postbackResponse_packet_digest_failure(),
                PayLinkPostbackNotice::class
            )
                ->licenceKey("MTD25HDPUVVBBG23")
                ->validate();

            $this->assertFalse(
                ($postbackNotice instanceof PayLinkPostbackNotice),
                "JSON packet should not be of type CityPay\PayLink\PayLinkPostbackNotice ("
                . get_class($postbackNotice)
                . ")"
            );
        } catch (Exception $e) {
            $this->assertFalse(
                ($e instanceof \LogicException),
                "Exception is a 'LogicException' ("
                . get_class($e)
                . ": "
                . $e->getMessage()
                . ")"
            );

            $this->assertTrue(
                ($e instanceof \RuntimeException),
                "Exception is not a subclass of 'RuntimeException' ("
                . get_class($e)
                . ")."
            );

        }
    }

    /**
     *
     */
    public function testPayLinkPostbackNotice_success()
    {
        $postbackNotice = JsonCodec::initialiseFrom(
            PayLinkPostbackNoticeUnitTest::postbackResponse_success(),
            PayLinkPostbackNotice::class
        );

        $this->assertTrue(
            ($postbackNotice instanceof PayLinkPostbackNotice),
            "JSON packet is not of type CityPay\PayLink\PayLinkPostbackNotice ("
            . get_class($postbackNotice)
            . ")"
        );

        $this->assertEquals(2000, $postbackNotice->getAmount());
        $this->assertEquals("383129", $postbackNotice->getAuthcode());
        $this->assertEquals(true, $postbackNotice->isAuthorised());
        $this->assertEquals("GBP", $postbackNotice->getCurrency());
        $this->assertEquals("000", $postbackNotice->getErrorCode());
        $this->assertEquals("Accepted Transaction", $postbackNotice->getErrorMessage());
        $this->assertEquals("ExampleIdent", $postbackNotice->getIdentifier());
        $this->assertEquals(123456, $postbackNotice->getMerchantId());
        $this->assertEquals("live", $postbackNotice->getMode());
        $this->assertEquals(1, $postbackNotice->getResult());
        $this->assertEquals(14152, $postbackNotice->getTransNo());
        $this->assertEquals("VD", $postbackNotice->getCardSchemeId());
        $this->assertEquals("Visa Debit", $postbackNotice->getCardScheme());
        $this->assertEquals(7, $postbackNotice->getExpMonth());
        $this->assertEquals(2018, $postbackNotice->getExpYear());
        $this->assertEquals("400000******0004", $postbackNotice->getMaskedPan());
        $this->assertEquals("Somewhere, Over the Rainbow", $postbackNotice->getAddress());
        $this->assertEquals("GB", $postbackNotice->getCountry());
        $this->assertEquals("support@citypay.com", $postbackNotice->getEmail());
        $this->assertEquals("Jo", $postbackNotice->getFirstName());
        $this->assertEquals("Bloggs", $postbackNotice->getLastName());
        $this->assertEquals("L12345", $postbackNotice->getPostCode());
        $this->assertEquals("", $postbackNotice->gettitle());
        $this->assertEquals("M", $postbackNotice->getAvsResponse());
        $this->assertEquals("M", $postbackNotice->getCscResponse());
        $this->assertEquals("AAABBTR2UCRUOZQCR3ZQAAAAAAA=", $postbackNotice->getCavv());
        $this->assertEquals("5", $postbackNotice->getEci());
        $this->assertEquals("Y", $postbackNotice->getAuthenticationResult());
        $this->assertEquals("C5s33819SkMqk0F2ZCDlOQ==", $postbackNotice->getDigest());
        $this->assertEquals("L66ss5eQuBbDuUBPS7j5TQP7HDo=", $postbackNotice->getSha1());
        $this->assertEquals("CP+2/luf2HfMmYRjqABP3j/xRoJ0fRjQisuSYIhB31k=", $postbackNotice->getSha256());
    }



}