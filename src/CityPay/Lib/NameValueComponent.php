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
     * @return mixed|void
     */
    protected function toJson() {
        $jsonCodec = new JsonCodec();
        return $jsonCodec->encode($this);
    }

    /**
     *
     */
    protected function toJsonSerializationForm() {
        return $this->mapNameValue;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return $this->mapNameValue;
    }

    /**
     * @return mixed|void
     */
    protected function toXml() {
        $xmlCodec = new XmlCodec();
        return $xmlCodec->encode($this);
    }
 }