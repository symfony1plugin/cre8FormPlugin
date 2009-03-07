<?php
/**
* sfExtraWidgetFormYesNo render a select radio widget
* with tow choices by default.
* 
* This widget is most pratical for boolean choices.
* You can create a radio button choice or a select widget.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormYesNo extends sfWidgetForm
{
    public function configure($options = array(), $attributes = array())
    {
        $this->addOption('choices', array(1 => 'Yes', 0 => 'No'));
        $this->addOption('type', 'radio'); // Available options : radio - select
    }
    
	public function render($name, $value = 0, $attributes = array(), $errors = array())
	{
        if($this->getOption('type') != 'select')
        {
            $radioWidget = new sfExtraWidgetFormSelectRadio(array('choices' => $this->getOption('choices')));
            return $radioWidget->render($name, $value, $attributes, $errors);
        }
        else
        {
            $selectWidget = new sfWidgetFormSelect(array('choices' => $this->getOption('choices')));
            return $selectWidget->render($name, $value, $attributes, $errors);
        }
	}
}
?>