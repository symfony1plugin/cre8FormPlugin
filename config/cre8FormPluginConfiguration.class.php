<?php

/**
 * cre8FormPlugin configuration.
 * 
 * @package     cre8FormPlugin
 * @subpackage  config
 * @author      Bogumil Wrona <bogumil.wrona@gmail.com>
 * @version     SVN: $Id: PluginConfiguration.class.php 12675 2008-11-06 08:07:42Z Kris.Wallsmith $
 */
class cre8FormPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if(!sfConfig::get('sf_rich_text_fck_js_dir')) {
      sfConfig::set('sf_rich_text_fck_js_dir', $this->getName().DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'fckeditor');
    }
    /*
    if(sfConfig::get('app_swToolbox_autoload_helper', true))
    {
      $this->loadHelpers(array('swToolbox'));
    }
    */
  }
}
