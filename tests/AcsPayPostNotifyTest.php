<?php
require(__DIR__.'/../vendor/autoload.php');

use CityPay\PayPost\AcsPayPostRequest;
use CityPay\PayPost\PayPostAuthenticationRequiredResponse;
use CityPay\PayPost\AcsPayPostNotify;
use CityPay\PayPost\PayPostResponse;

class AcsPayPostNotifyTest
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
    const ACS_NOTIFY = 1;
    
    //
    //
    //
    const REFUND = 2;
    
    //
    //
    //
    const CANCEL = 3;
    
    //
    //
    //
    const COMPLETE = 4;
    
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
            
            case self::ACS_NOTIFY:
                $apiMessage = $payPostRequest->notifyAcsResult();
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

    public function testThreeDSSale() {
        //
        //
        //
        $appr = (new AcsPayPostRequest())
            ->merchantId(self::getElectronicCommerceHighPassMID())
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->identifier("PayPost_PHP_SDK_ThreeDSSaleTest")
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
        $payPostAuthenticationRequired = self::execute(
            self::SALE,
            "testThreeDSSale",
            $appr
        );
        
        $this->assertTrue(
            ($payPostAuthenticationRequired instanceof PayPostAuthenticationRequiredResponse),
            "\$payPostAuthenticationRequired is not of type PayPostAuthenticationRequiredResponse ("
                .get_class($payPostAuthenticationRequired)
                .")"
        );
        
        var_dump($payPostAuthenticationRequired);
        
        //
        //  Need a mock MPI in here to generate a "valid" paRes from the
        //  paReq contained in the PayPostAuthenticationRequiredResponse.
        //
        
        $paRes = $payPostAuthenticationRequired->getPaReq();
        $md = $payPostAuthenticationRequired->getMd();
        
        $acsPayPostNotify = (new AcsPayPostNotify())
            ->merchantId(self::getElectronicCommerceHighPassMID())
            ->licenceKey(self::getElectronicCommerceHighPassLicenceKey())
            ->paRes($paRes)
            ->md($md);
        
        $payPostResponse = self::execute(
            self::ACS_NOTIFY,
            "testThreeDSSale",
            $acsPayPostNotify
        );
        
        var_dump($payPostResponse);
    }
}

