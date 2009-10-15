<?php if($sf_user->hasFlash('notice')): ?>
<?php echo $sf_user->getFlash('notice'); ?>
<?php else: ?>
<form action="<?php echo url_for('cre8ForgottenPassword/index'); ?>" method="post" name="forgottenPasswordForm" enctype="application/x-www-form-urlencoded">
  <?php
    echo $form;
  ?>
  <input type="submit" value="submit" />
</form>
<?php endif; ?>