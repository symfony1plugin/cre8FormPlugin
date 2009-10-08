<?php

class cre8ImageValidatorFile extends sfValidatorFile  
{
    
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addOption('min_width');
    $this->addOption('max_width');
    $this->addOption('min_height');
    $this->addOption('max_height');
    $this->addOption('exact_width');
    $this->addOption('exact_height');
    $this->addOption('min_ratio');
    $this->addOption('max_ratio');
    $this->addOption('exact_ratio');
    
    $this->addMessage('min_width', 'Image has a width too small');
    $this->addMessage('max_width', 'Image has a width too big');
    $this->addMessage('min_height', 'Image has a height too small');
    $this->addMessage('max_height', 'Image has a height too big');
    $this->addMessage('exact_width', 'Image has an incorrect width');
    $this->addMessage('exact_height', 'Image has an incorrect height');
    $this->addMessage('min_ratio', 'Image has an incorrect aspect ratio');
    $this->addMessage('max_ratio', 'Image has an incorrect aspect ratio');
    $this->addMessage('exact_ratio', 'Image has an incorrect aspect ratio');

  }

  protected function doClean($value)
  {
    $obj = parent::doClean($value);
    
    list($width, $height, $image_type) = @getimagesize($value['tmp_name']);
    
    if(!$width || !$height || !$image_type) {
      return $obj;
    }
    
    $min_width = $this->getOption('min_width');
    if ($min_width !== null && $min_width > $width) {
      throw new sfValidatorError($this, 'min_width');
    }

    $max_width = $this->getOption('max_width');
    if ($max_width !== null && $max_width < $width) {
      throw new sfValidatorError($this, 'max_width');
    }

    $min_height = $this->getOption('min_height');
    if ($min_height !== null && $min_height > $height) {
      throw new sfValidatorError($this, 'min_height');
    }

    $max_height = $this->getOption('max_height');
    if ($max_height !== null && $max_height < $height) {
      throw new sfValidatorError($this, 'max_height');
    }
    
    $exact_width = $this->getOption('exact_width');
    if ($exact_width !== null && $exact_width != $width) {
      throw new sfValidatorError($this, 'exact_width');
    }

    $exact_height = $this->getOption('exact_height');
    if ($exact_height !== null && $exact_height != $height) {
      throw new sfValidatorError($this, 'exact_height');
    }

    $aspect = $this->getOption('exact_ratio');
    if ($aspect !== null && (is_float($aspect) || is_int($aspect)) && $aspect != $width/$height) {
      throw new sfValidatorError($this, 'exact_ratio');
    }
    
    $aspect_min = $this->getOption('min_ratio');
    if($aspect_min !==null && ( is_float($aspect_min) || is_int($aspect_min) ) && $aspect_min >= $width/$height) {
      throw new sfValidatorError($this, 'min_ratio');
    }
    
    $aspect_max = $this->getOption('max_ratio');
    if($aspect_max !==null && ( is_float($aspect_max) || is_int($aspect_max) ) && $aspect_max <= $width/$height) {
      throw new sfValidatorError($this, 'max_ratio');
    }
    
    return $obj;
  }
  
}
