<?php
require(__DIR__.'/../../vendor/autoload.php');

use CityPay\PayPost\PayPostRequest;
use CityPay\PayPost\AcsPayPostRequest;
use CityPay\PayPost\PayPostResponse;

class PayPostRequestIntegrationTest
    extends PHPUnit_Framework_TestCase
{
    use CityPay\Lib\ClientConfiguration,
        CityPay\Lib\ThreeDSecureConfiguration {
//        \CityPay\Lib\ClientConfiguration::initTrait insteadof \CityPay\Lib\ThreeDSecureConfiguration;
//        \CityPay\Lib\ClientConfiguration::initTrait as initClientConfigurationTrait;
//        \CityPay\Lib\ThreeDSecureConfiguration::initTrait as initThreeDSecureConfigurationTrait;
    }
    
    /**
     * 
     */
    const SALE = 0;
    
    /**
     * 
     */
    const REFUND = 1;
    
    /**
     * 
     */
    const CANCEL = 2;
    
    /**
     * 
     */
    const COMPLETE = 3;
    
    /**
     * 
     */
//    public static function setUpBeforeClass() {
//        self::initClientConfigurationTrait();
//        self::initThreeDSecureConfigurationTrait();
//    }
    
    /**
     * 
     * @param type $action
     * @param type $caller
     * @param \CityPay\PayPost\PayPostRequest $payPostRequest
     * @return type
     */
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

    /**
     * 
     */
    public function testSuccessfulSale()
    {
        $ppr = new \CityPay\PayPost\PayPostRequest();
        
        $ppr->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_SuccessfulSaleTest")
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

    /**
     * 
     */
    public function testDeclinedSale()
    {
        $ppr = new \CityPay\PayPost\PayPostRequest();
        
        $ppr->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_DeclinedSaleTest")
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
    
    /**
     * 
     */
    public function testThreeDSSale() {
        //
        //
        //
        $appr = (new AcsPayPostRequest())
            ->merchantId(self::getElectronicCommerceHighPassMID())
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->identifier("PayPost_PHPSDK_ThreeDSSaleTest")
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
        $apiMessage = self::execute(
            self::SALE,
            "testThreeDSSale",
            $appr
        );
        
        $this->assertTrue(
            ($apiMessage instanceof CityPay\PayPost\PayPostAuthenticationRequiredResponse),
            "ApiMessage [apr] is not of type \CityPay\PayPost\PayPostAuthenticationRequiredResponse ("
                .get_class($apiMessage)
                .")"
        );
        
        $paRes = \CityPay\Mock\ThreeDSecureAccessControlServer::processPaymentAuthorisationRequest(
            $this,
            $apiMessage->getPaReq(),
            'Y',
            array(
                \CityPay\Mock\ThreeDSecureAccessControlServer::REG_EX_EXPECTED_PAREQ_PACKET
                    => self::getPayPostRequestTestThreeDSSalePAReqPacket(),
                \CityPay\Mock\ThreeDSecureAccessControlServer::REG_EX_EXPECTED_PARES_PACKET
                    => self::getPayPostRequestTestThreeDSSalePAResPacket()
            )
        );
        
        $appn = (new CityPay\PayPost\AcsPayPostNotify())
          ->merchantId(self::getElectronicCommerceHighPassMID())
          ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
          ->md($apiMessage->getMd())
          ->paRes($paRes);
        
        $apiMessage = $appn->notifyAcsResult();
        
        $this->assertTrue(
            get_class($apiMessage) == 'CityPay\PayPost\PayPostResponse',
            'Assert response object is not of type CityPay\PayPost\PayPostResponse'
        );
        
        $this->assertTrue(
            $apiMessage->getAuthcode() == 'A12345',
            "Authcode is not A12345 ("
                .$apiMessage->getAuthcode()
        );
     
        $this->assertTrue(
            $apiMessage->isAuthorised(),
            "Transaction was not authorised"
        );
        
        $this->assertTrue(
            $apiMessage->getMode() == 'test',
            "Transaction was not executed in test mode."
        );
    }
    
    /**
     * 
     */
    public function testCardHolderAccountSetupAndContinuousAuthorityTransaction() {
        //
        //
        //
        $ppr_cha = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceCardholderAccountMID())
            ->licenceKey(self::getElectronicCommerceCardholderAccountLicenceKey())
            ->identifier("PayPost_PHPSDK_CHA_CA_Test_SetupTX")
            ->amount(5000)
            ->currency("GBP")
            ->cardNumber("4000000000000002")
            ->expiryMonth(12)
            ->expiryYear(2016)
            ->accountNo("ABC")
            ->firstname("James")
            ->lastname("Square");
        
        $ppr_cha->billToName("James T Square")
            ->billToPostCode("JE2 3RL")
            ->csc("123");
        
        $apiMessage = self::execute(
            self::SALE,
            "testCardHolderAccountSetupAndContinuousAuthorityTransaction",
            $ppr_cha
        );
        
        $this->assertTrue(
            ($apiMessage instanceof PayPostResponse),
            "ApiMessage [ppr_cha] is not of type \CityPay\PayPost\PayPostResponse ("
                .get_class($apiMessage)
                .")"
        );
        
        $this->assertTrue(
            $apiMessage->isAuthorised(),
            "Cardholder Account creating PayPostRequest failed"
        );
        
        $ppr_ca = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceContinuousAuthorityMID())
            ->licenceKey(self::getElectronicCommerceContinuousAuthorityLicenceKey())
            ->identifier("PayPost_PHPSDK_CHA_CA_Test_CATX")
            ->amount(6000)
            ->currency("GBP")
            ->accountNo("ABC");
        
        $apiMessage = self::execute(
            self::SALE,
            "testCardHolderAccountSetupAndContinuousAuthorityTransaction",
            $ppr_ca
        );

        $this->assertTrue(
            ($apiMessage instanceof PayPostResponse),
            "ApiMessage [ppr_ca] is not of type \CityPay\PayPost\PayPostResponse ("
                .get_class($apiMessage)
                .")"
        );
        
        $this->assertTrue(
            $apiMessage->isAuthorised(),
            "Continuous Authority transaction PayPostRequest failed"
        );
    }

    /**
     * 
     */
    public function testRefund() {
        //
        //
        //

    }

    /**
     * 
     */
    public function testSuccessfulPreAuthoriseAndCancel() {
        //
        //
        //
        $ppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_SuccessPreAuthCancelTest")
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

    /**
     * 
     */
    public function testSuccessfulPreAuthoriseAndComplete() {
        //
        //
        //
        $ppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_SuccessPreAuthCompleteTest")
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

    /**
     * 
     */
    public function testComplete() {
        //
        //
        //
        $cppr = (new PayPostRequest())
            ->merchantId(self::getElectronicCommerceLowPassMID())
            ->licenceKey(self::getElectronicCommerceLowPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_CompleteSaleTest")
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