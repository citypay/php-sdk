<?php
namespace CityPay\PayLink;

use CityPay\Lib\ApiMessage;

/**
 * 
 */
class PayLinkToken
    extends ApiMessage
{
    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $url;

    /**
     * @param $id
     * @param $url
     */
    function __construct(
        $id,
        $url
    ) {
        parent::__construct();
        $this->id = $id;
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getURL() {
        return $this->url;
    }
}