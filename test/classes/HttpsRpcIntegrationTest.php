<?php
require(__DIR__.'/../../vendor/autoload.php');

use CityPay\PayLink\PayLinkRequest;
use CityPay\PayLink\Address;
use CityPay\PayLink\Cardholder;
use CityPay\PayLink\Configuration;

/**
 * 
 */
class HttpsRpcIntegrationTest
    extends PHPUnit_Framework_TestCase
{
    use \CityPay\Lib\ClientConfiguration {
        \CityPay\Lib\ClientConfiguration::initTrait as initClientConfigurationTrait;
    }
    
    /**
     * 
     */
    public static function setUpBeforeClass() {
        self::initClientConfigurationTrait();
    }
    
    /**
     *
     */
    public function testSantizePayload()
    {
        //$payload =


    }

    public function testHttpsRpc()
    {
        $pr = new PayLinkRequest();

        $address = (new Address())
            ->address1("<addressLine1>")
            ->address2("<addressLine2>")
            ->area("<area>");

        $cardholder = (new Cardholder())
            ->title("<title>")
            ->firstName("<firstName>")
            ->lastName("<lastName>")
            ->address($address);

        $configuration = (new Configuration())
            ->postbackPolicy("sync")
            ->postback("http://postback/address")
            ->redirect("http://general/redirect")
            ->redirectSuccess("http://success/redirect")
            ->redirectFailure("http://failure/redirect")
            ->options(
                array(Configuration::BYPASS_CUSTOMER_EMAIL,
                Configuration::BYPASS_MERCHANT_EMAIL,
                Configuration::BYPASS_AVS_ADDRESS,
                Configuration::BYPASS_AVS_POSTCODE,
                Configuration::ENFORCE_CSC_REQUIRED)
            );

        $pr->merchantId(self::getElectronicCommerceHighPassMID())
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->identifier("PaymentRequestGoodRequestPacketTest")
            ->amount(5000)
            ->currency("GBP")
            ->cardholder($cardholder)
            ->configuration($configuration);
    }
}