<?php
require(__DIR__.'/../../vendor/autoload.php');

use CityPay\Lib\Logger;

use CityPay\PayLink\PayLinkRequest;
use CityPay\PayLink\Address;
use CityPay\PayLink\Cardholder;
use CityPay\PayLink\Configuration;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * 
 */
class CityPayLoggingIntegrationTest
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
    private static function getEchoLoggerConfig() {
        return array(
            'rootLogger' => array(
                'appenders' => array('default')
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderEcho',
                    'layout' => array(
                        'class' => 'LoggerLayoutSimple'
                    ),
                    'params' => array(
                        'htmlLineBreaks' => 'false'
                    )
                )
            )
        );
    }
    
    /**
     * 
     */
    public static function getFileLoggerConfig() {
        return array(
            'rootLogger' => array(
                'appenders' => array('default')
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderEcho',
                    'layout' => array(
                        'class' => 'LoggerLayoutSimple'
                    ),
                    'params' => array(
                        'htmlLineBreaks' => 'false'
                    )
                )
            )
        );
    }
    
    /**
     * 
     */
    public static function getLoggerConfig() {
        return static::getEchoLoggerConfig();
    }
    
    /**
     *
     */
    public function testCityPayDefaultLogger()
    {
        $logger = Logger::getLogger(__CLASS__);
        $this->assertTrue($logger instanceof LoggerInterface);
        
        $logger->emergency("EMERGENCY message");
        $logger->alert("ALERT message");
        $logger->critical("CRITICAL message");
        $logger->error("ERROR message");
        $logger->warning("WARNING message");
        $logger->notice("NOTICE message");
        $logger->info("INFO message");
        $logger->debug("DEBUG message");
        $logger->log(LogLevel::INFO, "INFO message, via log");
        
        $this->assertTrue(true);
    }
    
    /**
     * 
     */
    public function testCityPayPciDssLogger()
    {
        $logger = Logger::getLogger(__CLASS__);
        Logger::configure(static::getEchoLoggerConfig());
        $this->assertTrue($logger instanceof LoggerInterface);
        
        $logger->emergency("EMERGENCY message");
        $logger->alert("ALERT message");
        $logger->critical("CRITICAL message");
        $logger->error("ERROR message");
        $logger->warning("WARNING message");
        $logger->notice("NOTICE message");
        $logger->info("INFO message");
        $logger->debug("DEBUG message");
        $logger->log(LogLevel::INFO, "INFO message, via log");
        
        $this->assertTrue(true);
    }
    
    /**
     * 
     */
    public function testPayLinkRequest_failure() {
        $logger = Logger::getLogger(__CLASS__);
        Logger::configure(static::getEchoLoggerConfig());
        $this->assertTrue($logger instanceof LoggerInterface);
        
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
    
    /**
     * 
     */
    public function testPayLinkRequest_success() {
        $logger = Logger::getLogger(__CLASS__);
        Logger::configure(static::getEchoLoggerConfig());
        $this->assertTrue($logger instanceof LoggerInterface);
        
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