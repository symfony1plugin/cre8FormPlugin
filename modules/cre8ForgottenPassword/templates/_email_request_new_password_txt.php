Hello <?php echo $sfGuardUser->getProfile()->getFirstName() ?>,

Use this link: <?php echo url_for('@cre8ForgottenPassword_reset_password?key=' . $sfGuardUser->getSalt(), array('absolute' => true)); ?> to generate new password.