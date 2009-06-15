<?
class sfWidgetFormDateDynarch extends sfWidgetFormInput
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * type: The widget type (text by default)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('timezone', 'GMT');
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

//    if(!$value) $value = "now";
//    //transform for timezone
//    $value = dbDateTime::getForTimezone(dbDateTime::dateAsDateTime($value), timezone_open($this->getOption("timezone")), "Y-m-d H:i:s");
    return parent::render($name, $value, $attributes, $errors).
           $this->renderTag('image', array('id' => $prefix.'_trigger', 'src' => '/sf/sf_admin/images/date.png')).

"<script type=\"text/javascript\">
  Calendar.setup(
    {
      inputField  : \"$prefix\",            // ID of the input field
      button      : \"{$prefix}_trigger\",  // ID of the button
      ifFormat    : \"%Y-%m-%d %G:%M:%S\",
      daFormat    : \"%Y-%m-%d %G:%M:%S\",
      showOthers  : true,
      showsTime   : true
    }
  );
</script>";
  }

  public function getStylesheets()
  {
    return array(
      "/dbFormExtraPlugin/dynarch/calendar-system.css" => 'all',
      "/dbFormExtraPlugin/dynarch/skins/aqua/theme.css" => 'all',
    );
  }

  public function getJavaScripts()
  {
  	return array(
  	     "/dbFormExtraPlugin/dynarch/calendar.js" => "/dbFormExtraPlugin/dynarch/calendar.js",
         "/dbFormExtraPlugin/dynarch/lang/calendar-en.js" => "/dbFormExtraPlugin/dynarch/lang/calendar-en.js",
         "/dbFormExtraPlugin/dynarch/calendar-setup.js" => "/dbFormExtraPlugin/dynarch/calendar-setup.js"
         );
  }
}
?>