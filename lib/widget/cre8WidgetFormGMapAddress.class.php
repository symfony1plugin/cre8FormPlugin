<?php

class cre8WidgetFormGMapAddress extends swWidgetFormGMapAddress 
{
  
  public function getJavascripts()
  {
    return array(
      '/cre8FormPlugin/js/swGmapWidget.js'
    );
  }
}