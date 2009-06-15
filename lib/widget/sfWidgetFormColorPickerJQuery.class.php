<?php

class sfWidgetFormColorPickerJQuery extends sfWidgetFormInput
{



  /**
   * Gets the Stylesheets paths associated with the widget.
   *
   * @return array An array of Stylesheets paths
   */
  public function getStylesheets()
  {
    return array('/dbFormExtraPlugin/farbtastic/farbtastic.css');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/dbFormExtraPlugin/farbtastic/farbtastic.js');
  }

 public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    use_helper("jQuery");

    $attributes['style'] = "float:left;";
    $html = parent::render($name, $value, $attributes, $errors);

    $html .= "
    <div id=\"colorpicker\" style=\"float:left;margin-left:20px;\"></div>
   <script type=\"text/javascript\">
     $(document).ready(function() {
    $('#colorpicker').farbtastic('#".$this->generateId($name)."');
  });

  </script>
    ";

    return $html;
  }
}
