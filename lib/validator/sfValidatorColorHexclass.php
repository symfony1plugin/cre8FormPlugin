<?php

/*
 * This file is part of the dbFormExtraPlugin package.
 * (c) Digital Base <info@digitalbase.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorColorHex validates that the value is a hexadecimal color code in following formats #FFF444, #DDD333
 *
 * @package    symfony
 * @subpackage validator
 */
class sfValidatorColorHex extends sfValidatorBase
{
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if (!preg_match('/^#(?:(?:[a-f\d]{3}){1,2})$/i', $value)) {
      throw new sfValidatorError($this, 'invalid color', array('value' => $value));
    }

    return $value;
  }
}
