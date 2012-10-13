<?php

class BaseCre8ForgottenPasswordActions extends sfActions 
{
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new Cre8ForgottenPasswordForm();
    if($request->isMethod('post') && $request->getParameter('forgotten_password'))
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
        
        $this->getMailer()->composeAndSend(
          sfConfig::get('app_company_email', 'b.wrona@cre8newmedia.com'),
          $sfGuardUser->getUsername(),
          'New password',
          $this->getPartial('cre8ForgottenPassword/email_request_new_password_txt', $emailParameters)
        );
        
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
    
    $this->getMailer()->composeAndSend(
      sfConfig::get('app_company_email', 'b.wrona@cre8newmedia.com'),
      $sfGuardUser->getUsername(),
      'New password',
      $this->getPartial('cre8ForgottenPassword/email_generated_password_txt', $emailParameters)
    );
    
  }
  
  public function executeInvalidKey(sfWebRequest $request)
  {
    if($redirectTo = sfConfig::get('app_cre8ForgottenPassword_invalid_key_redirect')) {
      $this->redirect($redirectTo);
    }
    
  }
  
  
}