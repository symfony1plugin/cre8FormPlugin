<?php

/**
 * Form Formatter which generates asterixs "*" to all labels where field is required
 *
 * In configure():
 * $decorator = new cre8FormFormatter($this->widgetSchema, $this->validatorSchema);
 * $this->widgetSchema->addFormFormatter('custom', $decorator);
 * $this->widgetSchema->setFormFormatterName('custom');
 */
class cre8FormFormatter extends sfWidgetFormSchemaFormatter 
{
  protected 
    
    $currentFormElementName = null,
  
    $rowFormat       = "<li class=\"cre8FormRow cre8FormRow_%field_id%\">\n  %label%\n  %field%%error%%help%\n%hidden_fields%</li>\n",
//    $errorRowFormat  = "<li>\n%errors%</li>\n",
    $helpFormat      = '<div class="fieldHelp cre8FormFieldHelp_%field_id%">&uarr; %help%</div>',
    $decoratorFormat = "<ul>\n  %content% \n</ul>",
    
//    $rowFormat = "\n%error%\n<div class=\"formRow\">\n<div class=\"formLabel\">%label%</div>\n<div class=\"formField\">%field%\n%help%</div></div>\n%hidden_fields%",
    $errorRowFormat = "<div>\n%errors%<br /></div>\n", 
//    $helpFormat = '<div class="fieldHelp">%help%</div>', 
//    $decoratorFormat = "%content%",
    
    $errorListFormatInARow = "<ul class=\"error_list\">%errors%</ul>\n", 
    $errorRowFormatInARow =  "<li class=\"error\">&larr; %error%</li>\n", 
    $namedErrorRowFormatInARow = "<li class=\"error\">&larr; %error%</li>\n";
    
 
  /**
   * @var sfValidatorSchema
   */
  protected $validatorSchema = null;
 
  /**
   * @var array
   */
  protected $params = array();
 
  /**
   * Constructor
   *
   * Params:
   *  - "required_label_class_name" css class name for label tag when the field is required field, the default is 'required'.
   *  - "required_label_format" default is '%label% <em class="required">*</em>'.
   *
   * @param sfWidgetFormSchema $widgetSchema
   * @param sfValidatorSchema $validatorSchema
   * @param array $params
   */
  public function __construct(sfWidgetFormSchema $widgetSchema, sfValidatorSchema $validatorSchema, $params = array())
  {
    $this->validatorSchema = $validatorSchema;
    $this->params = $params;
    parent::__construct($widgetSchema);
  }
 
  /**
   * Returns parameter identified with $name or if does not exist, returns $default.
   *
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function getParameter($name, $default=null)
  {
    if (!isset($this->params[$name])) {
      return $default;
    }
    return $this->params[$name];
  }
 
  /**
   * Generates a label for the given field name.
   *
   * @param  string $name        The field name
   * @param  array  $attributes  Optional html attributes for the label tag
   *
   * @return string The label tag
   */
  public function generateLabel($name, $attributes = array())
  {
    if($this->isFieldRequired($name)) {
      $class_name = $this->getParameter('required_label_class_name', 'required');
      if (isset($attributes['class'])) $attributes['class'] .= ' '.$class_name; else $attributes['class'] = $class_name;
    }
 
    $s = $this->cre8Label($name, $attributes);
 /*
    if ($is_required)
    {
      $format = $this->getParameter('required_label_format', '%label%<em class="required">*</em>');
      $s = str_replace('%label%', $s, $format);
    }
 */
    return $s;
  }
  
  /**
   * Generates a label for the given field name.
   *
   * @param  string $name        The field name
   * @param  array  $attributes  Optional html attributes for the label tag
   *
   * @return string The label tag
   */
  public function cre8Label($name, $attributes = array()) 
  {
    $this->currentFormElementName = $name;
    $labelName = $this->generateLabelName($name);
    if (false === $labelName) {
      return '';
    }
    $labelName .= ':';
    if($this->isFieldRequired($name)) {
      $labelName .= '<em class="required">*</em>';
    }
    if (!isset($attributes['for'])) {
      $attributes['for'] = $this->widgetSchema->generateId($this->widgetSchema->generateName($name));
    }

    return $this->widgetSchema->renderContentTag('label', $labelName, $attributes);
  }
  
  protected function getFieldValidator($name) {
    return ( $this->validatorSchema && isset($this->validatorSchema[$name]) ) ? $this->validatorSchema[$name] : null;
  }
  
  protected function isFieldRequired($name) {
    return ( ($validator = $this->getFieldValidator($name)) && $validator->getOption('required')) ? true : false;
  }
  
  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    return strtr($this->getRowFormat(), array(
      '%label%'         => $label,
      '%field%'         => $field,
      '%error%'         => $this->formatErrorsForRow($errors),
      '%help%'          => $this->formatHelp($help),
      '%hidden_fields%' => is_null($hiddenFields) ? '%hidden_fields%' : $hiddenFields,
      '%field_id%'		=> $this->currentFormElementName ? $this->widgetSchema->generateId($this->widgetSchema->generateName($this->currentFormElementName)) : 'R' . rand(101, 999)
    ));
  }
  
}