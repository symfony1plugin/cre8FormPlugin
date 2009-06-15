<?php 
/**
* sfExtraWidgetFormInputNumeric render a datepicker widget.
* 
* This class render a simple input widget with a spin to select
* the number like in visual basic.
* 
* Warning : This class use Prototype to generate the calendar,
* make sure that the prototype plugin is loaded.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormInputSpin extends sfWidgetFormInput
{
    public function configure($options = array(), $attributes = array())
    {
        $this->setAttribute('autocomplete', 'off');
        $this->addOption('min', 0);
        $this->addOption('max', 99999);
        
        parent::configure($options, $attributes);
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addJavascript('/sfExtraWidgetsPlugin/js/spinbutton.js');
        $response->addStylesheet('/sfExtraWidgetsPlugin/css/spinbutton/spinbutton.css');
        
        return parent::render($name, $value, $attributes, $errors) . 
        javascript_tag("new SpinButton($('" . $this->generateId($name) . "'),{min:" . $this->getOption('min') . ", max:" . $this->getOption('max') . "}); $('" . $this->generateId($name) . "').addClassName('spin-button');");
    }
}
?>