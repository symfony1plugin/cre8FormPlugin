<?php 
/**
* sfExtraWidgetFormInputAutocomplete render an ajax autocompleter
* 
* This class render a simple input widget, but when you type any
* characters a proposition list will appear below the input widget.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormInputAutocomplete extends sfWidgetFormInput
{
    public function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('url');
        $this->addOption('param', 'autocomplete');
        $this->addOption('min_chars', 2);
        
        parent::configure($options, $attributes);
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addStylesheet('/sfExtraWidgetsPlugin/css/autocompleter/autocompleter.css');

        $autocompleteDiv = content_tag('div' , '', array('id' => $this->generateId($name) . '_autocomplete', 'class' => 'autocomplete'));
        
        $autocompleteJs = javascript_tag("
            function ac_update_" . $this->generateId($name) . "(text, li)
            {
                $('" . $this->generateId($name) . "').value = li.id;
            }
            
            new Ajax.Autocompleter(
                '" . $this->generateId($name) . "',
                '" . $this->generateId($name) . '_autocomplete' . "',
                '" . url_for($this->getOption('url')) . "',
                {
                    paramName: '" . $this->getOption('param') . "',
                    indicator: 'indicator-" . $this->generateId($name) . "',
                    minChars: " . $this->getOption('min_chars') . ",
                    afterUpdateElement: ac_update_" . $this->generateId($name) . "
                });"
            );
        
        return parent::render($name, $value, $attributes, $errors) . 
        '<span id="indicator-' . $this->generateId($name) . '" style="display: none;">&nbsp;&nbsp;<img src="/sfExtraWidgetsPlugin/img/ajax-loader.gif" align="absmiddle" alt="Loading" /></span>' . 
        $autocompleteDiv . 
        $autocompleteJs;
    }
}
?>