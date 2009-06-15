<?php 
/**
* sfExtraWidgetFormSelectLanguage render an select box for language selection
* 
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraWidgetFormSelectCulture extends sfWidgetFormSelect
{
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        
        $culture = sfContext::getInstance()->getUser()->getCulture();
        
        $this->addOption('culture', $culture);
        $this->addOption('languages');

        $languages = sfCultureInfo::getInstance($culture)->getLanguages(isset($options['languages']) ? $options['languages'] : null);
        $cultures = sfCultureInfo::getInstance($culture)->getCultures();
        $countries = sfCultureInfo::getInstance($culture)->getCountries();
        
        $values = array();
        
        foreach($cultures as $key => $culture_info)
        {
            if(strlen($culture_info) == 5)
            {
                $culture_small = substr($culture_info, 0, 2);
                $countrie_small = substr($culture_info, 3, 2);
               
                if(array_key_exists($culture_small, $languages) && array_key_exists($countrie_small, $countries))
                {
                    $select_language = preg_replace('/^[' . $culture_small . ']{2}/i', $languages[$culture_small], $culture_info);
                    $select = preg_replace('/[' . $countrie_small . ']{2}$/i', '(' . $countries[$countrie_small] . ')', $select_language);
                    
                    $values[$culture_info] = ucfirst(str_replace('_', ' ', $select));
                }
            }
        }
        
        if(count($values) == 0)
        {
            $values[''] = 'No languages found';
        }
        
        asort($values);
        
        $this->setOption('choices', $values);
    }
}
?>