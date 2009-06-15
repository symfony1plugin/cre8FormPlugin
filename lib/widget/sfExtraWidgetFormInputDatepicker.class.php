<?php 
/**
* sfExtraWidgetFormInputDatepicker render a datepicker widget.
* 
* This class render a simple input widget with an icon at the end.
* When you click on the icon an ajax calendar will be show to help
* the date selection.
* 
* Warning : This class use Prototype to generate the calendar,
* make sure that the prototype plugin is loaded.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormInputDatepicker extends sfWidgetFormInput
{
    public function configure($options = array(), $attributes = array())
    {
        $this->addOption('style', 'adobe');
        parent::configure($options, $attributes);
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addJavascript('/cre8FormPlugin/js/datepicker.js');
        $response->addStylesheet('/cre8FormPlugin/css/datepicker/datepicker_' . $this->getOption('style') . '.css');
        
        $picker = '&nbsp;<img src="/cre8FormPlugin/img/datepicker/datepicker_icon.png" align="absmiddle" id="' . $this->generateId($name) . '_icon" alt="" style="cursor:pointer;" onclick="dp_display(\'' . $this->generateId($name) . '\')" />';
        
        if(!is_null($value) && $value != '')
        {
            if(strtotime($value) != '')
            {
                $value = format_date(date('Y-m-d', strtotime($value)), sfConfig::get('app_datepicker_output_sf_format'));
            }
        }
        
        return parent::render($name, $value, $attributes, $errors) . $picker;
    }
}
?>