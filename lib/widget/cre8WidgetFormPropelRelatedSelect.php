<?php

class cre8WidgetFormPropelRelatedSelect extends sfWidgetFormPropelSelect 
{
  protected $tag_id = null;
  
  public function __construct(sfWidget $widget, $options = array(), $attributes = array())
  {
    $this->tag_id = $widget->getAttribute('id');
    if(! $this->tag_id) {
      throw new RuntimeException(sprintf('TAG ID is not set in a "%s" widget', __CLASS__));
    }
    parent::__construct($options, $attributes);
  }
  
  /**
   * Available options:
   *
   *  * model:       The model class (required)
   *  * cid:         The model 'category id' field example: 'category_id' (required)
   *  * url:         The url of the action(routing, or module/action) to be called using Ajax (required)
   *  * event:		 The DOM event of the select tag that will trigger the Ajax call (onchange by default)
   *  * add_empty:   Whether to add a first empty value or not (false by default)
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
    $choices = array();
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->getOption('add_empty');
    }

    $class = constant($this->getOption('model').'::PEER');

    $criteria = is_null($this->getOption('criteria')) ? new Criteria() : clone $this->getOption('criteria');
    if ($order = $this->getOption('order_by'))
    {
      $method = sprintf('add%sOrderByColumn', 0 === strpos(strtoupper($order[1]), 'ASC') ? 'Ascending' : 'Descending');
      $criteria->$method(call_user_func(array($class, 'translateFieldName'), $order[0], BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME));
    }
    $objects = call_user_func(array($class, $this->getOption('peer_method')), $criteria, $this->getOption('connection'));

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model'), $methodKey))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodKey, __CLASS__));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
    }

    foreach ($objects as $object)
    {
      $choices[$object->$methodKey()] = $object->$methodValue();
    }

    return $choices;
  }
  
  public function render($name, $value  = null, $attributes = array(), $errors = array())
  {
    $html = parent::render($name, $value, $attributes, $errors);
    // add ajax code to the defined event
    $html .= sprintf(<<<EOF
<script type="text/javascript" charset="utf-8">
  document.observe('dom:loaded', function() {
  	$('%s').observe('%s', function(evt) {
  		new Ajax.Request('%s', {
          method: 'post',
          parameters: { fid: $('%s').value },
          onSuccess: function(transport) {
            alert(transport.responseText);
          	/*
          	var parsed = [];
            for(key in data) {
            	parsed[parsed.length] = { data: [data[key], key], value: data[key] }
            }
            */
          }
        });
  	});
  });
</script>
EOF
      ,
      $this->generateId($name),
      $this->getOption('event'),
      url_for($this->getOption('url')),
      $update,
      $optional
    );

    return $html;
  }
  
}