<?php 
/**
* sfExtraWidgetFormRichTextarea render an rich text editor
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormRichTextarea extends sfWidgetFormTextarea
{
    public function configure($options = array(), $attributes = array())
    {        
        $this->setAttribute('class', 'mceEditor');
        $this->addOption('width', 650);
        $this->addOption('height', 300);
        
        parent::configure($options, $attributes);
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $response = sfContext::getInstance()->getResponse();
        $response->addJavascript('/sfExtraWidgetsPlugin/js/tinymce/plugins/tinybrowser/tb_tinymce.js.php');
        $response->addJavascript('/sfExtraWidgetsPlugin/js/tinymce/tiny_mce.js');

        $js = "
            tinyMCE.init({
                // General options
                mode : 'exact',
                elements: '" . $this->generateId($name) . "',
                skin : '" . sfConfig::get('app_tinymce_skin', 'o2k7') . "',
                language: '" . sfContext::getInstance()->getUser()->getCulture() . "',
                theme : 'advanced',
                height: " . $this->getOption('height') . ",
                width: " . $this->getOption('width') . ",
                dialog_type: '" . sfConfig::get('app_tinymce_dialog_type', 'window') . "',
                editor_selector : 'mceEditor',
                editor_deselector : 'mceNoEditor',
                relative_urls : " . sfConfig::get('app_tinymce_relative_urls', 'false') . ",
                gecko_spellcheck: " . sfConfig::get('app_tinymce_gecko_spellcheck', 'true') . ",
                entity_encoding : '" . sfConfig::get('app_tinymce_entity_encoding', 'raw') . "',
                plugins : '" . sfConfig::get('app_tinymce_plugins') . "',

                // Theme options
                theme_advanced_buttons1 : '" . sfConfig::get('app_tinymce_theme_advanced_buttons1') . "',
                theme_advanced_buttons2 : '" . sfConfig::get('app_tinymce_theme_advanced_buttons2') . "',
                theme_advanced_buttons3 : '" . sfConfig::get('app_tinymce_theme_advanced_buttons3') . "',
                theme_advanced_toolbar_location : '" . sfConfig::get('app_tinymce_theme_advanced_toolbar_location') . "',
                theme_advanced_toolbar_align : '" . sfConfig::get('app_tinymce_theme_advanced_toolbar_align') . "',
                theme_advanced_statusbar_location : '" . sfConfig::get('app_tinymce_theme_advanced_statusbar_location') . "',
                theme_advanced_resizing : " . sfConfig::get('app_tinymce_theme_advanced_resizing') . ",
                
                file_browser_callback : '" . sfConfig::get('app_tinymce_file_browser_callback') . "'
            });
        ";
        
        return javascript_tag($js) . parent::render($name, $value, $attributes, $errors);
    }
}
?>