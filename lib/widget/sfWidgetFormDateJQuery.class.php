<?php

/*
 * This file is part of the symfony package.
 * (c) Gijs Nelissen <gijs.nelissen@digitalbase.eu>
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDateJQuery represents a date widget rendered by JQuery UI.
 * It was based on the sfWidgetFormJQueryDate class included in the sfFormExtraPlugin the things we changed :
 *  1/ included jquery in the release
 *  2/ made sure css/js is automatically loaded
 *  3/ fixed javascript issue
 *  4/ included fileroller jquery theme
 *
 * This widget does not need JQuery or JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryDate.class.php 12875 2008-11-10 12:22:33Z fabien $
 */
class sfWidgetFormDateJQuery extends sfWidgetFormDate
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:   The image path to represent the widget (false by default)
   *  * config:  A JavaScript array that configures the JQuery date widget
   *  * culture: The user culture
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');

    parent::configure($options, $attributes);

    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $prefix = $this->generateId($name);

    $image = '';
    if (false !== $this->getOption('image'))
    {
      $image = sprintf(', buttonImage: %s, buttonImageOnly: true', $this->getOption('image'));
    }

    return parent::render($name, $value, $attributes, $errors).
           $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name).'_jquery_control', 'disabled' => 'disabled')).
           sprintf(<<<EOF
<script type="text/javascript">
jQuery.noConflict();
  function %s_read_linked()
  {
    jQuery("#%s").val(jQuery("#%s").val() + "/" + jQuery("#%s").val() + "/" + jQuery("#%s").val());

    return {};
  }

  function %s_update_linked(date)
  {
    jQuery("#%s").val(date.substring(3, 5));
    jQuery("#%s").val(date.substring(0, 2));
    jQuery("#%s").val(date.substring(6, 10));
  }

  jQuery("#%s").datepicker(jQuery.extend({}, {
    minDate:    new Date(%s, 1 - 1, 1),
    maxDate:    new Date(%s, 12 - 1, 31),
    beforeShow: %s_read_linked,
    onSelect:   %s_update_linked,
    showOn:     "both"
    %s
  }, jQuery.datepicker.regional["%s"], %s));
</script>
EOF
      ,
      $prefix, $id,
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $prefix,
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $id,
      min($this->getOption('years')), max($this->getOption('years')),
      $prefix, $prefix, $image, $this->getOption('culture'), $this->getOption('config')
    );
  }
  
  public function getStylesheets() {
  	return array(
  		sfConfig::get("app_dbformextraplugin_jqueryui_path_css") . "ui.core.css" => 'all',
  		sfConfig::get("app_dbformextraplugin_jqueryui_path_css") . "ui.theme.css" => 'all',
  		sfConfig::get("app_dbformextraplugin_jqueryui_path_css") . "ui.datepicker.css" => 'all',
  	);
  }
  
  public function getJavascripts() {
  	return array(
  	    sfConfig::get("app_dbformextraplugin_jquery_path") . sfConfig::get("app_dbformextraplugin_jquery_version") => sfConfig::get("app_dbformextraplugin_jquery_path") .  sfConfig::get("app_dbformextraplugin_jquery_version"),
  	    sfConfig::get("app_dbformextraplugin_jqueryui_path") . "ui.calendar.js"  => sfConfig::get("app_dbformextraplugin_jqueryui_path") . "ui.calendar.js",
         );
  	
  	
  }


}
