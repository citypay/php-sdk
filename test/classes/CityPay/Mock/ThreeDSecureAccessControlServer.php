<?php
namespace CityPay\Mock;

use \CityPay\Lib\ThreeDSecureUtils;

class ThreeDSecureAccessControlServer
{
    const REG_EX_EXPECTED_PAREQ_PACKET = 'RegExExpectedPAReqPacket';
    const REG_EX_EXPECTED_PARES_PACKET = 'RegExExpectedPAResPacket';
    
    //
    //
    //
    use \CityPay\Lib\XmlHelper;
    
    public static function processPaymentAuthorisationRequest(
        $testCase,
        $paReq,
        $status,
        $options
    ) {       
        //
        //  Decode PAReq packet
        //
       
        $paReq_xml = ThreeDSecureUtils::decode($paReq);
        if (array_key_exists(self::REG_EX_EXPECTED_PAREQ_PACKET, $options)) {
            $testCase->assertTrue(
                preg_match("/"
                    .$options[self::REG_EX_EXPECTED_PAREQ_PACKET]
                    ."/", $paReq_xml) == 1,
                "3DS PAReq packet received by test case logic does not match expected input: \n"
                    .$paReq_xml
            );
        }

        //
        //  Parse XML packet to generate a DOM
        //
        $paReq_dom = new \DOMDocument();
        $paReq_dom->loadXML($paReq_xml);
        $paReq_message = self::getFirstElementByTagName($paReq_dom, "Message");
        $messageId = $paReq_message->getAttribute("id");
        
        //
        //  Extract requisite fields
        //
        
        $paReq_element = self::getFirstElementByTagName($paReq_message, "PAReq");
        $paReq_version = self::getFirstElementByTagName($paReq_element, "version");
        $version = self::getText($paReq_version);

        $paReq_merchant = self::getFirstElementByTagName($paReq_element, "Merchant");
        $paReq_acqBin = self::getFirstElementByTagName($paReq_merchant, "acqBin");
        $acqBin = self::getText($paReq_acqBin);
            
        $paReq_merId = self::getFirstElementByTagName($paReq_merchant, "merID");
        $merId = self::getText($paReq_merId);
        
        $paReq_purchase = self::getFirstElementByTagName($paReq_element, "Purchase");
        
        $paReq_xid = self::getFirstElementByTagName($paReq_purchase, "xid");
        $xid = self::getText($paReq_xid);
        
        $paReq_date = self::getFirstElementByTagName($paReq_purchase, "date");
        $date = self::getText($paReq_date);
        
        $paReq_purchase_amount = self::getFirstElementByTagName($paReq_purchase, "purchAmount");
        $purchAmount = self::getText($paReq_purchase_amount);
            
        $paReq_currency = self::getFirstElementByTagName($paReq_purchase, "currency");
        $currency = self::getText($paReq_currency);
        
        $paReq_exponent = self::getFirstElementByTagName($paReq_purchase, "exponent");
        $exponent = self::getText($paReq_exponent);
        
        $paReq_ch = self::getFirstElementByTagName($paReq_element, "CH");
        $paReq_acctId = self::getFirstElementByTagName($paReq_ch, "acctID");
        $acctId = self::getText($paReq_acctId);
            
        //
        //  Create and encode PARes packet
        //
        $paRes_dom = new \DOMDocument();
        $threeDSecureElement = $paRes_dom->createElement("ThreeDSecure");
        $paRes_dom->appendChild($threeDSecureElement);
        
        $messageElement = $paRes_dom->createElement("Message");
        $messageElement->setAttribute(
            "id",
            $messageId
        );
        $threeDSecureElement->appendChild($messageElement);
        
        $paResElement = $paRes_dom->createElement("PARes");
        $messageElement->appendChild($paResElement);
        
        $versionElement = $paRes_dom->createElement("version");
        $paResElement->appendChild($versionElement);
        
        $versionText = $paRes_dom->createTextNode("1.0.2");
        $versionElement->appendChild($versionText);
        
        $merchantElement = $paRes_dom->createElement("Merchant");
        $paResElement->appendChild($merchantElement);
            
        $acqBinElement = $paRes_dom->createElement("acqBin");
        $merchantElement->appendChild($acqBinElement);
        
        $acqBinText = $paRes_dom->createTextNode($acqBin);
        $acqBinElement->appendChild($acqBinText);
          
        $merIdElement = $paRes_dom->createElement("merID");
        $merchantElement->appendChild($merIdElement);
 
        $merIdText = $paRes_dom->createTextNode($merId);
        $merIdElement->appendChild($merIdText);
        
        $purchaseElement = $paRes_dom->createElement("Purchase");
        $paResElement->appendChild($purchaseElement);
        
        $xidElement = $paRes_dom->createElement("xid");
        $purchaseElement->appendChild($xidElement);
        
        $xidText = $paRes_dom->createTextNode($xid);
        $xidElement->appendChild($xidText);
        
        $dateElement = $paRes_dom->createElement("date");
        $purchaseElement->appendChild($dateElement);
        
        $dateText = $paRes_dom->createTextNode($date);
        $dateElement->appendChild($dateText);
        
        $purchAmountElement = $paRes_dom->createElement("purchAmount");
        $purchaseElement->appendChild($purchAmountElement);
        
        $purchAmountText = $paRes_dom->createTextNode($purchAmount);
        $purchAmountElement->appendChild($purchAmountText);
        
        $currencyElement = $paRes_dom->createElement("currency");
        $purchaseElement->appendChild($currencyElement);
        
        $currencyText = $paRes_dom->createTextNode($currency);
        $currencyElement->appendChild($currencyText);
        
        $exponentElement = $paRes_dom->createElement("exponent");
        $purchaseElement->appendChild($exponentElement);
        
        $exponentText = $paRes_dom->createTextNode($exponent);
        $exponentElement->appendChild($exponentText);
        
        $panElement = $paRes_dom->createElement("pan");
        $paResElement->appendChild($panElement);
        
        $panText = $paRes_dom->createTextNode($acctId);
        $panElement->appendChild($panText);
        
        $txElement = $paRes_dom->createElement("TX");
        $paResElement->appendChild($txElement);
        
        $timeElement = $paRes_dom->createElement("time");
        $txElement->appendChild($timeElement);
        
        $timeText = $paRes_dom->createTextNode($date);
        $timeElement->appendChild($timeText);
             
        $statusElement = $paRes_dom->createElement("status");
        $txElement->appendChild($statusElement);
        
        $statusText = $paRes_dom->createTextNode($status);
        $statusElement->appendChild($statusText);
        
        if ($status === 'Y') {
            
            $cavvElement = $paRes_dom->createElement("cavv");
            $txElement->appendChild($cavvElement);
            
            $cavvText = $paRes_dom->createTextNode("jIrBSEdMHBzGABEAAAGE/aM/mc4=");
            $cavvElement->appendChild($cavvText);
            
            $eciElement = $paRes_dom->createElement("eci");
            $txElement->appendChild($eciElement);
            
            $eciText = $paRes_dom->createTextNode("05");
            $eciElement->appendChild($eciText);
            
            $cavvAlgorithmElement = $paRes_dom->createELement("cavvAlgorithm");
            $txElement->appendChild($cavvAlgorithmElement);
            
            $cavvAlgorithmText = $paRes_dom->createTextNode("3");
            $cavvAlgorithmElement->appendChild($cavvAlgorithmText);
            
        } else {
            
            $eciElement = $paRes_dom->createElement("eci");
            $txElement->appendChild($eciElement);
            
            $eciText = $paRes_dom->createTextNode("06");
            $eciElement->appendChild($eciText);
            
        }
        
        $xml_out = $paRes_dom->saveXML();
        
        if (array_key_exists(self::REG_EX_EXPECTED_PARES_PACKET, $options)) {
            $testCase->assertTrue(
                preg_match(
                    "/"
                        . $options[self::REG_EX_EXPECTED_PARES_PACKET]
                        ."/", $xml_out) == 1,
                "3DS PARes packet generated by test case logic does not match expected output: \n"
                    .$xml_out
                    ."\n"
                    .$options[self::REG_EX_EXPECTED_PARES_PACKET]
            );
        }
        
        return ThreeDSecureUtils::encode($xml_out);
    }
}