<?php

class BaseCre8ForgottenPasswordActions extends sfActions 
{
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new Cre8ForgottenPasswordForm();
    if($request->isMethod('post') && $request->getParameter('forgotten_password[fid]') == 'forgotten_password')
    {
      $this->form->bind($request->getParameter('forgotten_password'));
      if($this->form->isValid())
      {
        $values = $this->form->getValues();
        $c = new Criteria();
        $c->add(sfGuardUserPeer::USERNAME, $values['email']);
        $sfGuardUser = sfGuardUserPeer::doSelectOne($c);
        
        $emailParameters = array(
          'sfGuardUser' => $sfGuardUser
        );
        
        list($body, $part) = cre8Mail::getBodyAndAlternate('partial', 'cre8ForgottenPassword/email_request_new_password_html', 'cre8ForgottenPassword/email_request_new_password_txt', $emailParameters);
        $options = array(
          'parts'        => array( $part )
        );
        cre8Mail::send('New password', $body, $sfGuardUser->getUsername(), $options);
        
        $this->getUser()->setFlash('notice', 'An email has been sent to you with information how to reset your password.');
        
      }
    }
  }
  
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