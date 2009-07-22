Hello <?php echo $sfGuardUser->getProfile()->getFirstName() ?>,<br />
<br />
Click <?php echo link_to('here', url_for('@cre8ForgottenPassword_reset_password?key=' . $sfGuardUser->getSalt(), true)); ?> to generate new password.