<?php
namespace CityPay\Lib;

use CityPay\Encoding\FormUrl\FormUrlCodec;
use CityPay\Encoding\Json\JsonCodec;
use CityPay\Encoding\Xml\XmlCodec;

/**
 * 
 */
trait NameValueComponent
{
    /**
     * @var array
     */
    private $mapNameValue;

    /**
     *
     */
    function initialiseNameValueComponent(
        $apiConfig = \CityPay\Config\DefaultConfig::class
    ) {
        $this->mapNameValue = array();
    }

    public function get(
        $name
    ) {
        return $this->mapNameValue[$name];
    }
    
    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set(
        $name,
        $value
    ) {
        $this->mapNameValue[$name] = $value;
    }

    /**
     *
     */
    protected function toFormUrl() {
        $formUrlCodec = new FormUrlCodec();
        return $formUrlCodec->encode($this);
    }

    /**
     * @return a Json string encoded via associated data from jsonSerialize
     */
    function toJson() {
        return JsonCodec::encode($this->jsonSerialize());
    }


    /**
     * @return array for external use
     */
    function jsonSerialize()
    {
        $data = [];
        foreach ($this->mapNameValue as $key => $value) {
            if (is_object($value) && method_exists($value,'jsonSerialize')) {
                $value = $value->jsonSerialize();
            }
            $data[$key] = $value;
        }
        return  $data;
    }


    public function formUrlSerialize()
    {
        // TODO: Implement formUrlSerialize() method.
    }

    /**
     * @return mixed|void
     */
    protected function toXml() {
        $xmlCodec = new XmlCodec();
        return $xmlCodec->encode($this);
    }
 }