<?php
/**
* sfExtraWidgetFormSelectRadio render a select radio widget
* with a special formatter.
* 
* This formatter will create the radio widget side by side 
* separated by a space.
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormSelectRadio extends sfWidgetFormSelectRadio
{
	public function configure($options = array(), $attributes = array())
    {
		$this->addRequiredOption('choices');
		$this->addOption('label_separator', '&nbsp;');
		$this->addOption('separator', "\n");
		$this->addOption('formatter', array($this, 'formatter_radio'));
	}

	public function formatter_radio($widget, $inputs)
    {
		$rows = array();
		foreach ($inputs as $input){

			$rows[] = $this->renderContentTag('span', $input['input'].$this->getOption('label_separator'). $input['label']);
		}

		return $this->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => 'radio_list'));
	}
}
?>