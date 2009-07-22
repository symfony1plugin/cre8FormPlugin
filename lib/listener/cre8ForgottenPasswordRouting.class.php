<?php

class cre8ForgottenPasswordRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $r->prependRoute('cre8ForgottenPassword_reset_password', new sfRoute('/reset_password/:key', array('module' => 'cre8ForgottenPassword', 'action' => 'resetPassword')));
  }
}