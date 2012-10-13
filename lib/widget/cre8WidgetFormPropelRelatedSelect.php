<?php

class cre8WidgetFormPropelRelatedSelect extends sfWidgetFormPropelChoice
{
  protected $tag_id = null;
  
  public function __construct(sfWidget $widget, $options = array(), $attributes = array())
  {
    if(! $widget->getAttribute('id')) {
      throw new RuntimeException(sprintf('HTML attribute "id" is not set for passed widget as parameter in a "%s" widget', __CLASS__));
    }
    $this->tag_id = $widget->getAttribute('id');
    
    parent::__construct($options, $attributes);
  }
  
  /**
   * Available options:
   *
   *  * model:       The model class (required)
   *  * cid:         The model 'category id' field example: 'category_id' (required)
   *  * url:         The url of the action(routing, or module/action) to be called using Ajax (required)
   *  * event:		 The DOM event of the select tag that will trigger the Ajax call (onchange by default)
   *  * add_empty:   Whether to add a first empty value or not (true by default)
   *                 If the option is not a Boolean, the value will be used as the text value
   *  * method:      The method to use to display object values (__toString by default)
   *  * key_method:  The method to use to display the object keys (getPrimaryKey by default)
   *  * order_by:    An array composed of two fields:
   *                   * The column to order by the results (must be in the PhpName format)
   *                   * asc or desc
   *  * criteria:    A criteria to use when retrieving objects
   *  * connection:  The Propel connection to use (null by default)
   *  * multiple:    true if the select tag must allow multiple selections
   *  * peer_method: The peer method to use to fetch objects
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addRequiredOption('cid');
    $this->addRequiredOption('url');
    $this->addOption('event', 'change');
    
    $this->setOption('add_empty', true);
  }
  
  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    return array('0' => 'Enable JavaScript');
  }
  
  public function render($name, $value  = null, $attributes = array(), $errors = array())
  {
    $html = parent::render($name, $value, $attributes, $errors);
    $jsFunctionName = 'update_' . $this->generateId($name);
    // add ajax code to the defined event
    $html .= " \n";
    $html .= sprintf(<<<EOF
<script type="text/javascript" charset="utf-8">
  document.observe('dom:loaded', function() {
  	$('%s').observe('%s', %s);
  	
  	%s();
  	
  	function %s() {
  		var html_id = '%s';
  		new Ajax.Request('%s', {
          method: 'post',
          parameters: { fid: $('%s').getValue() },
          onSuccess: function(transport) {
          	if(! transport.responseText.isJSON()) {
          		return;
          	}
          	var json = transport.responseText.evalJSON(true);
            cre8WidgetFormRelatedSelectUpdateSelectBox(html_id, json, '%s')
          },
          onException: function() {
  		  	return;
  		  }
        });
  	}
  	
  });
</script>
EOF
      ,
      $this->tag_id,
      $this->getOption('event'),
      $jsFunctionName,
      $jsFunctionName,
      $jsFunctionName,
      $this->generateId($name),
      url_for($this->getOption('url')),
      $this->tag_id,
      $value
    );

    return $html;
  }
  
  public function getJavascripts()
  {
    return array('/cre8FormPlugin/js/cre8WidgetFormRelatedSelect.js');
  }
  
}