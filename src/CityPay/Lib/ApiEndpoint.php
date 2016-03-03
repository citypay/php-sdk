<?php
namespace CityPay\Lib;

/**
 * 
 */
class ApiEndpoint
{
    private $url;
    
    /**
     * 
     */
    public function __construct(
        $url
    ) {
        $this->url = $url;
    }
    
    /**
     * 
     * @return type
     */
    public function getUrl() {
        return $this->url;
    }
}
