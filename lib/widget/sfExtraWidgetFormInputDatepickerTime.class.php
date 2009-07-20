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
class sfExtraWidgetFormInputDatepickerTime extends sfWidgetFormInput
{
    public function configure($options = array(), $attributes = array())
    {
        $this->addOption('culture', sfContext::getInstance()->getUser()->getCulture());
        $this->addOption('time', array());
        $this->addOption('separator', ' ');
        $this->addOption('style', 'adobe');
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addJavascript('/cre8FormPlugin/js/datepicker.js');
        $response->addStylesheet('/cre8FormPlugin/css/datepicker_' . $this->getOption('style') . '.css');
        
        $picker = '&nbsp;<img src="/cre8FormPlugin/img/datepicker/datepicker_icon.png" align="absmiddle" id="' . $this->generateId($name . '[date]') . '_icon" alt="" style="cursor:pointer;" onclick="dp_display(\'' . $this->generateId($name . '[date]') . '\')" />';

        $value_date = '';
        
        if (is_array($value))
        {
            $value_date = $value['date'];
        }
        elseif(!is_null($value) && $value != '')
        {
            $value_date = (string) $value == (string) (integer) $value ? (integer) $value : strtotime($value);
            $value_date = format_date(date('Y-m-d H:i:s', $value_date), sfConfig::get('app_datepicker_output_sf_format'));
        }
        
        return parent::render($name . '[date]', $value_date, $attributes, $errors) . $this->getOption('separator') . $this->getTimeWidget($attributes)->render($name, $value) . $picker;
    }

    protected function getTimeWidget($attributes = array())
    {
        return new sfWidgetFormI18nTime(array_merge(array('culture' => $this->getOption('culture')), $this->getOptionsFor('time')), $this->getAttributesFor('time', $attributes));
    }
    
    protected function getOptionsFor($type)
    {
        $options = $this->getOption($type);
        if (!is_array($options))
        {
            throw new InvalidArgumentException(sprintf('You must pass an array for the %s option.', $type));
        }

        return $options;
    }

    protected function getAttributesFor($type, $attributes)
    {
        $defaults = isset($this->attributes[$type]) ? $this->attributes[$type] : array();

        return isset($attributes[$type]) ? array_merge($defaults, $attributes[$type]) : $defaults;
    }
}
?>