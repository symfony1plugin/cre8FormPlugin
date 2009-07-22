<?php

class BaseCre8ForgottenPasswordForm extends sfForm 
{
  public function configure()
  {
    $this->setWidgets(array(
      'email' => new sfWidgetFormInput(),
      'fid' => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'email' => new sfGuardValidatorUsernameOrEmail(array('trim' => true), array('required' => 'Your E-mail address is required.', 'invalid' => 'E-mail address not found please try again.')),
      'fid' => new sfValidatorString()
    ));
    
    $this->setDefault('fid', 'forgotten_password');
    
    $this->widgetSchema->setNameFormat('forgotten_password[%s]');
  }
}