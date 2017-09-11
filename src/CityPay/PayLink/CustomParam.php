<?php
namespace CityPay\PayLink;
/**
 * Definition of a custom parameter for usage in the Paylink Token API
 */
class CustomParam
    implements \CityPay\Lib\Security\PciDssLoggable
{
    use \CityPay\Lib\PciDssLoggableNameValueComponent;

    /**
     *
     */
    function __construct()
    {
    }

    /**
     * Refers to the name of the parameter used to specify the HTML form element name. This value can be referred back through the PostBack data.
     * @param string $value the value being set
     * @return CustomParam
     */
    public function name($value)
    {
        self::set("name", $value);
        return $this;
    }

    /**
     * (Optional) an initial value for the parameter as it appears on the Form. If your parameter is hidden, the value will be required
     * @param string $value the value being set
     * @return CustomParam
     */
    public function value($value)
    {
        self::set("value", $value);
        return $this;
    }

    /**
     * states whether the field is required or optional. When an element is required, validation will be performed on the end user's input form
     * @return CustomParam
     */
    public function isRequired()
    {
        self::set("required", true);
        return $this;
    }

    /**
     * a value to set as the HTML5 placeholder attribute which will render in modern browsers.
     * @param string $value the value being set
     * @return CustomParam
     */
    public function placeholder($value)
    {
        self::set("placeholder", $value);
        return $this;
    }

    /**
     * a label that should appear immediately before the field element in the generated HTML form, describing what the form is used for. If this value is not supplied, the name value will be used
     * @param string $value the value being set
     * @return CustomParam
     */
    public function label($value)
    {
        self::set("label", $value);
        return $this;
    }

    /**
     * refers to a boolean value that states whether the field is a locked field that prevents entry or amendment by the person completing the form.
     * @return CustomParam
     */
    public function isLocked()
    {
        self::set("locked", true);
        return $this;
    }

    /**
     * a string value which specifies the HTML5 validation logic which is checked before submitting the form for payment for example a value of "PC[0-9]*" will require a value such as PC1, PC123
     * @param string $value the value being set
     * @return CustomParam
     */
    public function pattern($value)
    {
        self::set("pattern", $value);
        return $this;
    }

    /**
     * a value which can allow grouping of custom elements. If no value is provided, a default addition parameters group is rendered.
    This allows logical grouping to the payment form for multiple elements. For example, 3 elements with the group of "Your Details" would be displayed with a heading of "Your Details" and each form element displayed under this heading. Any items not within this group will be displayed in their respective group titles or the default group.
    Groups are rendered alphabetically.
     * @param string $value the value being set
     * @return CustomParam
     */
    public function group($value)
    {
        self::set("group", $value);
        return $this;
    }



    /**
     * a value which allows you to order the position of elements in a grouping. For instance a value which has 1 will order first. Defaults to 0. Items can be pushed in front by using negative values.
     * @param string $value the value being set
     * @return CustomParam
     */
    public function order($value)
    {
        self::set("order", $value);
        return $this;
    }

    /**
     * refers to the type of HTML 5 field entry and which may include ' hidden ' to represent a hidden field, or any other permitted value for the ' type ' attribute of the HTML 5 input element.
     * For advanced usage, see the token api reference
     * @param string $value the value being set
     * @return CustomParam
     */
    public function fieldType($value)
    {
        self::set("fieldType", $value);
        return $this;
    }


    /**
     *
     *
     */
    protected function getPciDssLoggableSensitiveElementTypeMap()
    {
        return array();
    }
}