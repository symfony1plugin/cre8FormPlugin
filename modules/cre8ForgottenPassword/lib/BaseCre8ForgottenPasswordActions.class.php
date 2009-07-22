<?php

class BaseCre8ForgottenPasswordActions extends sfActions 
{
  public function executeResetPassword(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated()) {
      $this->redirect('@homepage');
    }
    
    $c = new Criteria();
    $c->add(sfGuardUserPeer::SALT, $request->getParameter('key'));
    $sfGuardUser = sfGuardUserPeer::doSelectOne($c);
    
    $this->forwardUnless($sfGuardUser, 'cre8ForgottenPassword', 'invalidKey');
    
    $newPassword = time();
    $sfGuardUser->setPassword($newPassword);
    $sfGuardUser->save();
    
    $emailParameters = array(
      'sfGuardUser' => $sfGuardUser,
      'password' => $newPassword
    );
    
    if($redirectTo = sfConfig::get('app_cre8ForgottenPassword_password_generated_redirect')) {
      $this->redirect($redirectTo);
    }
    
    # Load body from components myModule : myComponentHTML and myComponentPLAIN
    list($body, $part) = cre8Mail::getBodyAndAlternate('partial', 'cre8ForgottenPassword/email_generated_password_html', 'cre8ForgottenPassword/email_generated_password_txt', $emailParameters);
    # Build options array, with the message parts, add embedded images, and attach a ZIP file
    $options = array(
      'parts'        => array( $part )
    );
    // Embedded image can be magically used in the HTML body with <img src="%%IMG_logo%%" />
    cre8Mail::send('New password', $body, $sfGuardUser->getUsername(), $options);
    
    
  }
  
  public function executeInvalidKey(sfWebRequest $request)
  {
    if($redirectTo = sfConfig::get('app_cre8ForgottenPassword_invalid_key_redirect')) {
      $this->redirect($redirectTo);
    }
    
  }
  
  
}