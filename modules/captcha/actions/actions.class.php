<?php
class captchaActions extends sfActions
{
    public function executeGetImage($request)
    {
        $captcha = new Captcha();
        $captcha->generateImage($this->getUser()->getAttribute('captcha')); 

        return sfView::NONE;
    }
}
?>
