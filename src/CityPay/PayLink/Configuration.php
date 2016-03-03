<?php
namespace CityPay\PayLink;

/**
 * 
 */
class Configuration
    implements \CityPay\Lib\Security\PciDssLoggable
{
    /**
     * 
     */
    use \CityPay\Lib\PciDssLoggableNameValueComponent;
    
    /**
     *
     */
    const
        BYPASS_AVS_POSTCODE = 'BYPASS_AVS_POSTCODE',
        ENFORCE_AVS_POSTCODE = 'ENFORCE_AVS_POSTCODE',
        BYPASS_AVS_ADDRESS = 'BYPASS_AVS_ADDRESS',
        ENFORCE_AVS_ADDRESS = 'ENFORCE_AVS_ADDRESS',
        BYPASS_CSC_REQUIRED = 'BYPASS_CSC_REQUIRED',
        ENFORCE_CSC_REQUIRED = 'ENFORCE_CSC_REQUIRED',
        BYPASS_3DSECURE = 'BYPASS_3DSECURE',
        BYPASS_PREAUTH = 'BYPASS_PREAUTH',
        ENFORCE_PREAUTH = 'ENFORCE_PREAUTH',
        CREATE_CAC_ACCOUNT_ON_AUTHORISATION = 'CREATE_CAC_ACCOUNT_ON_AUTHORISATION',
        BYPASS_DUPLICATE_CHECK = 'BYPASS_DUPLICATE_CHECK',
        BYPASS_CUSTOMER_EMAIL = 'BYPASS_CUSTOMER_EMAIL',
        BYPASS_MERCHANT_EMAIL = 'BYPASS_MERCHANT_EMAIL';

    /**
     *
     */
    function __construct() { }

    /**
     * @param string $acsMode
     * @return \CityPay\PayLink\Configuration
     */
    public function acsMode(
        $acsMode
    ) {
        self::set("acsMode", $acsMode);
        return $this;
    }

    /**
     * @param string $customParams
     * @return \CityPay\PayLink\Configuration
     */
    public function customParams(
        $customParams
    ) {
        self::set("customParams", $customParams);
        return $this;
    }

    /**
     * @param string $expireIn
     * @return \CityPay\PayLink\Configuration
     */
    public function expireIn(
        $expireIn
    ) {
        self::set("expireIn", $expireIn);
        return $this;
    }

    /**
     * @param string $merchantBranding
     * @return \CityPay\PayLink\Configuration
     */
    public function merchantBranding(
        $merchantBranding
    ) {
        self::set("merch_branding", $merchantBranding);
        return $this;
    }

    /**
     * @param string $lockedParams
     * @return \CityPay\PayLink\Configuration
     */
    public function lockedParams(
        $lockedParams
    ) {
        self::set("lockParams", $lockedParams);
        return $this;
    }

    /**
     * @param string $options
     * @return \CityPay\PayLink\Configuration
     */
    public function options(
        $options
    ) {
        self::set("options", $options);
        return $this;
    }

    /**
     * @param string $postback
     * @return \CityPay\PayLink\Configuration
     */
    public function postback(
        $postback
    ) {
        self::set("postback", $postback);
        return $this;
    }

    /**
     * @param string $postbackPolicy
     * @return \CityPay\PayLink\Configuration
     */
    public function postbackPolicy(
        $postbackPolicy
    ) {
        self::set("postbackPolicy", $postbackPolicy);
        return $this;
    }

    /**
     * @param $redirect
     * @return \CityPay\PayLink\Configuration
     */
    public function redirect(
        $redirect
    ) {
        self::set("redirect", $redirect);
        return $this;
    }

    /**
     * @param string $redirectSuccess
     * @return \CityPay\PayLink\Configuration
     */
    public function redirectSuccess(
        $redirectSuccess
    ) {
        self::set("redirect_success", $redirectSuccess);
        return $this;
    }

    /**
     * @param string $redirectFailure
     * @return \CityPay\PayLink\Configuration
     */
    public function redirectFailure(
        $redirectFailure
    ) {
        self::set("redirect_failure", $redirectFailure);
        return $this;
    }

    /**
     * @param string $returnParams
     * @return \CityPay\PayLink\Configuration
     */
    public function returnParams(
        $returnParams
    ) {
        self::set("return_params", $returnParams);
        return $this;
    }

    /**
     * @param string $renderer
     * @return \CityPay\PayLink\Configuration
     */
    public function renderer(
        $renderer
    ) {
        self::set("renderer", $renderer);
        return $this;
    }
    
    /**
     * 
     * PciDssLoggable
     * 
     */
    protected function getPciDssLoggableSensitiveElementTypeMap() {
        return array();
    }
}
