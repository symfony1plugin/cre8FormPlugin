<?php
/**
* sfExtraWidgetFormCaptcha render an input with a captcha image
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormInputCaptcha extends sfWidgetFormInput
{    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $obj = link_to_function(
            image_tag(
                url_for('captcha/getImage?key=' . time()),
                array(
                    'id' => 'captcha_img', 
                    'alt' => 'Click if you cannot read the picture',
                    'align' => 'absmiddle'
                )
            ),
            'document.getElementById(\'captcha_img\').src=\'' . url_for('captcha/getImage?reload=1') . '&key=\'+Math.round(Math.random(0)*1000)+1+\'\''
        );
        
        return '<div style="padding-bottom: 5px;">' . $obj . '</div>' . parent::render($name, $value, $attributes, $errors);
    }
}
