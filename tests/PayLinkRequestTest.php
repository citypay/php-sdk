<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\PayLink\PayLinkRequest;
use CityPay\PayLink\Address;
use CityPay\PayLink\Cardholder;
use CityPay\PayLink\Configuration;

class PayLinkRequestTest
    extends PHPUnit_Framework_TestCase
{
    use ClientConfiguration;
    
    /**
     *
     */
    public function testSantizePayload()
    {
        //$payload =

    }

    public function testPayLinkRequest_failure()
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
                array(
                    Configuration::BYPASS_CUSTOMER_EMAIL,
                    Configuration::BYPASS_MERCHANT_EMAIL,
                    Configuration::BYPASS_AVS_ADDRESS,
                    Configuration::BYPASS_AVS_POSTCODE,
                    Configuration::ENFORCE_CSC_REQUIRED
                )
            );

        $pr->merchantId("")
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->identifier("PaymentRequestGoodRequestPacketTest")
            ->amount(5000)
            ->currency("GBP")
            ->cardholder($cardholder)
            ->configuration($configuration);

        $res = $pr->saleTransaction();

        $this->assertTrue(
            get_class($res) == 'CityPay\PayLink\PayLinkApiError',
            'Assert response object is of type CityPay\PayLink\PayLinkApiError'
        );
    }

    public function testPayLinkRequest_success()
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
            ->licenceKey(self::getClientLicenceKey())
            ->identifier("PaymentRequestGoodRequestPacketTest")
            ->amount(5000)
            ->currency("GBP")
            ->cardholder($cardholder)
            ->configuration($configuration);

        $res = $pr->saleTransaction();

        $this->assertTrue(
            get_class($res) == 'CityPay\PayLink\PayLinkToken',
            "Response object is of type "
                .get_class($res)
                .", not CityPay\PayLink\PayLinkToken as required."
        );
    }
}