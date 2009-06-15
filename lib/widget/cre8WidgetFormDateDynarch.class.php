<?php

class cre8WidgetFormDateDynarch extends sfWidgetFormDateDynarch 
{
  public function getStylesheets()
  {
    return array(
      "/cre8FormPlugin/dynarch/calendar-system.css" => 'all',
      "/cre8FormPlugin/dynarch/skins/aqua/theme.css" => 'all',
    );
  }

  public function getJavaScripts()
  {
  	return array(
  	     "/cre8FormPlugin/dynarch/calendar.js",
         "/cre8FormPlugin/dynarch/lang/calendar-en.js",
         "/cre8FormPlugin/dynarch/calendar-setup.js"
    );
  }
}