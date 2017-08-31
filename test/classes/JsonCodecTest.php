<?php
/**
 * Created by IntelliJ IDEA.
 * User: gary
 * Date: 31/08/2017
 * Time: 10:38
 */

use CityPay\Encoding\Json\JsonCodec;
use CityPay\PayLink\PayLinkRequest;
use CityPay\PayLink\Address;
use CityPay\PayLink\Cardholder;
use CityPay\PayLink\Configuration;

class JsonCodecTest extends PHPUnit_Framework_TestCase
{

    public function testCardholderEncode()
    {
        $address = (new Address())
            ->address1("<addressLine1>")
            ->address2("<addressLine2>")
            ->area("<area>");

        $cardholder = (new Cardholder())
            ->title("<title>")
            ->firstName("<firstName>")
            ->lastName("<lastName>")
            ->address($address);

        $this->assertEquals("{\"title\":\"<title>\",\"firstName\":\"<firstName>\",\"lastName\":\"<lastName>\",\"address\":{\"address1\":\"<addressLine1>\",\"address2\":\"<addressLine2>\",\"area\":\"<area>\"}}",
            $cardholder->toJson());

    }


    public function testPayLinkRequestEncode()
    {
        $address = (new Address())
            ->address1("<addressLine1>")
            ->address2("<addressLine2>")
            ->area("<area>");

        $cardholder = (new Cardholder())
            ->title("<title>")
            ->firstName("<firstName>")
            ->lastName("<lastName>")
            ->address($address);

        $pr = new PayLinkRequest();
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

        $pr->merchantId("12345")
            ->licenceKey("12345")
            ->identifier("Test")
            ->amount(500)
            ->currency("GBP")
            ->cardholder($cardholder)
            ->configuration($configuration);


        $expected = "{\"test\":\"true\",\"merchantId\":\"12345\",\"licenceKey\":\"12345\",\"identifier\":\"Test\",\"amount\":500,\"currency\":\"GBP\",\"cardholder\":{\"title\":\"<title>\",\"firstName\":\"<firstName>\",\"lastName\":\"<lastName>\",\"address\":{\"address1\":\"<addressLine1>\",\"address2\":\"<addressLine2>\",\"area\":\"<area>\"}},\"configuration\":{\"postbackPolicy\":\"sync\",\"postback\":\"http://postback/address\",\"redirect\":\"http://general/redirect\",\"redirect_success\":\"http://success/redirect\",\"redirect_failure\":\"http://failure/redirect\",\"options\":[\"BYPASS_CUSTOMER_EMAIL\",\"BYPASS_MERCHANT_EMAIL\",\"BYPASS_AVS_ADDRESS\",\"BYPASS_AVS_POSTCODE\",\"ENFORCE_CSC_REQUIRED\"]}}";
        $this->assertEquals($expected, $pr->toJson());

    }

}
