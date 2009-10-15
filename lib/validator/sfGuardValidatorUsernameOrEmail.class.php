<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Gordon Franke <gfranke@savedcite.com>
 * @version    SVN: $Id: sfGuardValidatorUsernameOrEmail.class.php 18401 2009-05-18 14:12:09Z gimler $
 */
class sfGuardValidatorUsernameOrEmail extends sfValidatorBase
{
  public function configure($options = array(), $messages = array())
  {
  }

  protected function doClean($value)
  {
    $clean = (string) $value;
    
    $c = new Criteria();
    $c->add(sfGuardUserPeer::USERNAME, $clean);
    // user exists?
    if ($sfGuardUser = sfGuardUserPeer::doSelectOne($c))
    {
    	return $value;
    }

    throw new sfValidatorError($this, 'invalid', array('value' => $value));
  }
}
