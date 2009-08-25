<?php

class BaseUniversalUserForm extends BasesfGuardUserForm 
{
  protected $pkName = null;

  public function configure()
  {
    unset(
      $this['is_super_admin'],
      $this['is_active'],
      $this['last_login'],
      $this['created_at'],
      $this['salt'],
      $this['algorithm'],
      $this['sf_guard_user_group_list'],
      $this['sf_guard_user_permission_list']
    );
    
    $this->widgetSchema['fid'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['fid'] = new sfValidatorString(array('required' => true, 'trim' => true));
    $this->setDefault('fid', 'uuf');
    
    $this->validatorSchema['username'] = new sfValidatorEmail(array('required' => true, 'trim' => true));
    
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password']->setOption('required', false);
    $this->validatorSchema['password']->setOption('trim', true);
    
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];
    $this->validatorSchema['password_again']->setOption('trim', true);
    
    $this->widgetSchema->moveField('password_again', 'after', 'password');
    
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array(
        'callback'  => array($this, 'validatorUniqueUsername')
      ), array())
    );
    
    if(sfConfig::get('app_universal_user_form_username_to_lower_case', true)) {
      $this->mergePostValidator(
        new sfValidatorCallback(array(
          'callback'  => array($this, 'validatorUsernameLowerCase')
        ), array())
      );
    }
    
    $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again', array(), array('invalid' => 'The two passwords must be the same.')));
    
    // profile form?
    $profileFormClass = sfConfig::get('app_sf_guard_plugin_profile_class', 'sfGuardUserProfile').'Form';
    if (class_exists($profileFormClass))
    {
      $profileForm = new $profileFormClass();
      unset($profileForm[$this->getPrimaryKey()]);
      unset($profileForm[sfConfig::get('app_sf_guard_plugin_profile_field_name', 'user_id')]);

      $this->mergeForm($profileForm);
    } 
    
    $this->widgetSchema->setNameFormat('uuf[%s]');
    
  }
  
  public function validatorUniqueUsername($validator, array $values)
  {
//    die('type: ' . get_class($validator));  sfValidatorCallback
    if (array_key_exists('username', $values))
    {
      $c = new Criteria();
      $c->add(sfGuardUserPeer::USERNAME, $values['username'], Criteria::LIKE);
      if(!$this->isNew()) {
        $c->add(sfGuardUserPeer::ID, $this->getObject()->getId(), Criteria::NOT_EQUAL);
      }
      if(sfGuardUserPeer::doSelectOne($c)) {
          $error = new sfValidatorError($validator, 'Email already exist in the system');
          // throw an error bound to the password field
          throw new sfValidatorErrorSchema($validator, array('username' => $error));
      }
    }
    return $values;
  }
  
  public function validatorUsernameLowerCase($validator, array $values)
  {
    if (array_key_exists('username', $values)) {
      $values['username'] = strtolower($values['username']);
    }
    return $values;
  }
 
  
  public function updateObject($values = null)
  {
    parent::updateObject($values);

    // update defaults for profile
    if (!is_null($profile = $this->getProfile()))
    {
      $values = $this->getValues();
      unset($values[$this->getPrimaryKey()]);

      $profile->fromArray($values, BasePeer::TYPE_FIELDNAME);
      $profile->save();
    }

    return $this->object;
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    // update defaults for profile
    if (!is_null($profile = $this->getProfile()))
    {
      $values = $profile->toArray(BasePeer::TYPE_FIELDNAME);
      unset($values[$this->getPrimaryKey()]);

      // update defaults for the main object
      if ($this->isNew)
      {
        $this->setDefaults(array_merge($values, $this->getDefaults()));
      }
      else
      {
        $this->setDefaults(array_merge($this->getDefaults(), $values));
      }
    }
  }

  protected function getProfile()
  {
    try
    {
      return $this->object->getProfile();
    }
    catch (sfException $e)
    {
      // no profile
      return null;
    }
  }

  protected function getPrimaryKey()
  {
    if (!is_null($this->pkName))
    {
      return $this->pkName;
    }

    $profileClass = sfConfig::get('app_sf_guard_plugin_profile_class', 'sfGuardUserProfile');
    if (class_exists($profileClass))
    {
      $tableMap = call_user_func(array($profileClass.'Peer', 'getTableMap'));
      foreach ($tableMap->getColumns() as $column)
      {
        if ($column->isPrimaryKey())
        {
          return $this->pkName = call_user_func(array($profileClass.'Peer', 'translateFieldname'), $column->getPhpName(), BasePeer::TYPE_PHPNAME, BasePeer::TYPE_FIELDNAME);
        }
      }
    }
  }
  
  public function removeAllFieldsExcept($fieldNames = array())
  {
    
    $fieldsArray = $this->getFields();
    foreach($fieldsArray as $key => $name)
    {
      if(! in_array($name, $fieldNames)) {
        unset($this[$name]);
      }
    }
  }
  
  public function getFields()
  {
    return array_keys($this->widgetSchema->getFields());
  }
  
}
