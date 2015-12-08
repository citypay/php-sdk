<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\PayPost\PayPostRequest;
use CityPay\PayPost\AcsPayPostRequest;
use CityPay\PayPost\PayPostResponse;

class PayPostRequestTest
    extends PHPUnit_Framework_TestCase
{
    use ClientConfiguration;
    
    //
    //
    //
    const SALE = 0;
    
    //
    //
    //
    const REFUND = 1;
    
    //
    //
    //
    const CANCEL = 2;
    
    //
    //
    //
    const COMPLETE = 3;
    
    private function execute(
        $action,
        $caller,
        $payPostRequest
    ) {
        //
        //
        //
        switch ($action) {
            case self::SALE:
                $apiMessage = $payPostRequest->saleTransaction();
                break;

            case self::REFUND:
                $apiMessage = $payPostRequest->refundTransaction();
                break;

            case self::CANCEL:
                $apiMessage = $payPostRequest->cancelTransaction();
                break;

            case self::COMPLETE:
                $apiMessage = $payPostRequest->completeTransaction();
                break;

            default:
                $apiMessage = null;
                break;
        }

        //
        //
        //
        $traceOutput = $caller
            ." - \n";
        
        //
        //
        //
        /*if ($apiMessage != null) {
            //
            //
            //
            $this->assertTrue(
                $apiMessage instanceof CityPay\PayPost\PayPostResponse,
                '$apiMessage is a '.get_class($apiMessage)
            );

            //
            //
            //
            //sb.append(apiMessage.dump());
        } else {
            $traceOutput .= "apiMessage is NULL";
        }*/

        //
        //
        //
        //logger.info(sb.toString());

        return $apiMessage;
    }
    
    /**
     *
     */
    public function testSantizePayload()
    {
        //$payload =

    }

    public function testSuccessfulSale()
    {
        $ppr = new \CityPay\PayPost\PayPostRequest();
        
        $ppr->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHPSDK_SuccessfulSaleTest")
            ->amount(5000)
            ->currency("GBP")
            ->billToName("Mr Square")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123");

        $apiMessage = self::execute(
            self::SALE,
            "testSuccessfulSaleTest",
            $ppr
        );

        $this->assertTrue(
            get_class($apiMessage) == 'CityPay\PayPost\PayPostResponse',
            'Assert response object is of type CityPay\PayPost\PayPostResponse'
        );
    }

    public function testDeclinedSale()
    {
        $ppr = new \CityPay\PayPost\PayPostRequest();
        
        $ppr->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHPSDK_DeclinedSaleTest")
            ->amount(3333)
            ->currency("GBP")
            ->billToName("Mr Round")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123");

        $apiMessage = self::execute(
            self::SALE,
            "testUnsuccessfulSaleTest",
            $ppr
        );
       
        $this->assertTrue(
            $apiMessage instanceof CityPay\PayPost\PayPostResponse,
            'Assert response object is of type CityPay\PayPost\PayPostResponse'
        );
    }
    

    public function testThreeDSSale() {
        //
        //
        //
        $appr = (new AcsPayPostRequest())
            ->merchantId(self::getElectronicCommerceHighPassMID())
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->identifier("PayPost_JavaSDK_ThreeDSSaleTest")
            ->amount(5000)
            ->currency("GBP")
            ->billToName("<name>")
            ->billToAddress("<addressLine1>, <addressLine2>, <area>")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123")
            ->merchantTermURL("https://")
            ->userAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:10.0) Gecko/20100101 Firefox/10.0")
            ->acceptHeaders("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");

        //
        //
        //
        self::execute(
            self::SALE,
            "testThreeDSSale",
            $appr
        );
    }

    public function testRefund() {
        //
        //
        //

    }

    public function testSuccessfulPreAuthoriseAndCancel() {
        //
        //
        //
        $ppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHPSDK_SuccessPreAuthCancelTest")
            ->amount(5000)
            ->currency("GBP")
            ->billToName("Mr Taggart")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123");

        //
        //  Force pre-authorised request
        //
        $ppr->preAuth(PayPostRequest::PRE_AUTHORISATION_POLICY_PRE_AUTHORISE);

        //
        //
        //
        $preAuthApiMessage = self::execute(
            self::SALE,
            "SuccessfulPreAuthoriseAndCancelTest",
            $ppr
        );

        $this->assertTrue($preAuthApiMessage instanceof PayPostResponse);

        $this->assertTrue($preAuthApiMessage->isAuthorised());

        if ($preAuthApiMessage->isAuthorised()) {
            //
            //
            //
            $cppr = (new PayPostRequest())
                ->merchantId(self::getElectronicCommerceLowPassMID())
                ->licenceKey(self::getElectronicCommerceLowPassLicenceKey());

            //
            //
            //
            //cppr.identifier(preAuthPayPostResponse.getIdentifier());
            $cppr->transNo($preAuthApiMessage->getTransNo());

            //
            //
            //
            $cancelApiMessage = self::execute(
                self::CANCEL,
                "SuccessfulPreAuthoriseAndCancelTest",
                $cppr
            );

            //
            //
            //
            $this->assertTrue($cancelApiMessage instanceof PayPostResponse);

            //
            //
            //
            $this->assertTrue($cancelApiMessage->getAuthResult() == 19);

        }
    }

    public function testSuccessfulPreAuthoriseAndComplete() {
        //
        //
        //
        $ppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_JavaSDK_SuccessPreAuthCompleteTest")
            ->amount(5000)
            ->currency("GBP")
            ->billToName("Mr Taggart")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123");

        //
        //  Force pre-authorised request
        //
        $ppr->preAuth(PayPostRequest::PRE_AUTHORISATION_POLICY_PRE_AUTHORISE);

        //
        //
        //
        $preAuthApiMessage = self::execute(
            self::SALE,
            "SuccessfulPreAuthoriseAndCompleteTest",
            $ppr
        );
       
        $this->assertTrue($preAuthApiMessage instanceof PayPostResponse);

        $this->assertTrue($preAuthApiMessage->isAuthorised());

        if ($preAuthApiMessage->isAuthorised()) {
            //
            //
            //
            $cppr = (new PayPostRequest())
                ->merchantId(self::getElectronicCommerceLowPassMID())
                ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
                ->amount(5000);

            //
            //
            //
            //cppr.identifier(preAuthPayPostResponse.getIdentifier());
            $cppr->transNo($preAuthApiMessage->getTransNo());

            //
            //
            //
            $completeApiMessage = self::execute(
                self::COMPLETE,
                "SuccessfulPreAuthoriseAndCompleteTest",
                $cppr
            );

            //
            //
            //
            $this->assertTrue($completeApiMessage instanceof PayPostResponse);

            //
            //
            //
            $this->assertTrue($completeApiMessage->getAuthResult() == 1);

        }
    }

    public function testComplete() {
        //
        //
        //
        $cppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_JavaSDK_CompleteSaleTest")
            ->amount(5000)
            ->currency("GBP")
            ->billToName("<name>")
            ->billToAddress("<addressLine1>, <addressLine2>, <area>")
            ->billToPostCode("JE2 3RL")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->csc("123");

        self::execute(
            self::COMPLETE,
            "CompleteTest",
            $cppr
        );
    }
}

