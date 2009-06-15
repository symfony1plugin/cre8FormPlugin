<?php

class cre8WidgetFormInputColorPicker extends sfWidgetFormInput 
{
  public function configure($options = array(), $attributes = array())
  {
      parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
      $response = sfContext::getInstance()->getResponse();
      $response->addJavascript('/cre8FormPlugin/js/colorpicker.js');
      sfProjectConfiguration::getActive()->loadHelpers('Javascript');
      return parent::render($name, $value, $attributes, $errors) . javascript_tag("ProColor.prototype.attachButton('" . $this->generateId($name) . "', { imgPath:'/cre8FormPlugin/img/colorpicker/procolor_win_', showInField: true });");
  }
}