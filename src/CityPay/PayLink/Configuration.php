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
     * @return Configuration
     */
    public function acsMode(
        $acsMode
    ) {
        self::set("acsMode", $acsMode);
        return $this;
    }

    /**
     * @param array $customParams an array of custom parameters to add to the config
     * @return Configuration
     */
    public function customParams($customParams) {
        self::set("customParams", $customParams);
        return $this;
    }

    /**
     * @param string $expireIn
     * @return Configuration
     */
    public function expireIn(
        $expireIn
    ) {
        self::set("expireIn", $expireIn);
        return $this;
    }

    /**
     * @param string $merchantBranding
     * @return Configuration
     */
    public function merchantBranding(
        $merchantBranding
    ) {
        self::set("merch_branding", $merchantBranding);
        return $this;
    }

    /**
     * @param string $merchantLogo A URL of a merchant logo to include.
     * The logo should be delivered over HTTPS and at a max file size of 20kb.
     * Allowed MIME types being:
     * - image/gif
     * - image/png
     * - image/jpg
     * - image/jpeg
     * 
     * @return $this
     */
    public function merchantLogo(
        $merchantLogo
    ) {
        self::set("merch_logo", $merchantLogo);
        return $this;
    }

    /**
     * @param string $merchantTerms A URL of the terms of payment that you wish
     * to be confirmed. If provided, a link and checkbox will be added that
     * will be required to be checked before payment may continue.
     * 
     * @return $this
     */
    public function merchantTerms(
        $merchantTerms
    ) {
        self::set("merch_terms", $merchantTerms);
        return $this;
    }

    /**
     * @param string $lockedParams
     * @return Configuration
     */
    public function lockedParams(
        $lockedParams
    ) {
        self::set("lockParams", $lockedParams);
        return $this;
    }

    /**
     * @param array $options
     * @return Configuration
     */
    public function options(
        $options
    ) {
        self::set("options", $options);
        return $this;
    }

    /**
     * @param string $postback
     * @return Configuration
     */
    public function postback(
        $postback
    ) {
        self::set("postback", $postback);
        return $this;
    }

    /**
     * @param string $postbackPolicy
     * @return Configuration
     */
    public function postbackPolicy(
        $postbackPolicy
    ) {
        self::set("postbackPolicy", $postbackPolicy);
        return $this;
    }

    /**
     * @param $redirect
     * @return Configuration
     */
    public function redirect(
        $redirect
    ) {
        self::set("redirect", $redirect);
        return $this;
    }

    /**
     * @param int $redirectDelay The delay (in seconds) before the customers
     * browser is redirected by the Paylink Payment Form on conclusion of a
     * successful transaction.
     * A value of 0 specifies immediate redirection.
     * Leaving this field unset, or setting it to a negative value, specifies
     * that no redirection should occur.
     * @return $this
     */
    public function redirectDelay(
        $redirectDelay
    ) {
        self::set("redirect_delay", $redirectDelay);
        return $this;
    }

    /**
     * @param string $redirectSuccess
     * @return Configuration
     */
    public function redirectSuccess(
        $redirectSuccess
    ) {
        self::set("redirect_success", $redirectSuccess);
        return $this;
    }

    /**
     * @param string $redirectFailure
     * @return Configuration
     */
    public function redirectFailure(
        $redirectFailure
    ) {
        self::set("redirect_failure", $redirectFailure);
        return $this;
    }

    /**
     * @param string $returnParams
     * @return Configuration
     */
    public function returnParams(
        $returnParams
    ) {
        self::set("return_params", $returnParams);
        return $this;
    }

    /**
     * @param string $renderer
     * @return Configuration
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
