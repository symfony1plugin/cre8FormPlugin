<?php 
/**
* sfExtraWidgetFormInputMoney render a money widget.
* 
* Warning : This class use Prototype to generate the calendar,
* make sure that the prototype plugin is loaded.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormInputMoney extends sfWidgetFormInput
{
    public function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('symbol');
        
        parent::configure($options, $attributes);
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addJavascript('/sfExtraWidgetsPlugin/js/money.js');
        $response->addStylesheet('/sfExtraWidgetsPlugin/css/money/money.css');
        
        return '<div id="container-' . $this->generateId($name) . '">' . parent::render($name, $value, $attributes, $errors) . '</div>' . 
        javascript_tag("new Money($('" . $this->generateId($name) . "'),{ symbol: '" . $this->getOption('symbol') . "'})");
    }
}
?>