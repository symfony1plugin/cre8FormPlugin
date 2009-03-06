<?
/**
	* FCK Form widget
	*
	* @package    ***
	* @subpackage lib
	* @author     $LastChangedBy: PoWl $
	* @version    SVN: $Id: fckFormWidget.php 81 2008-12-08 00:39:59Z PoWl $
	*/

class fckFormWidget extends sfWidgetFormTextarea
{
	/**
		* @param array $options     An array of options
		* @param array $attributes  An array of default HTML attributes
		*
		* @see sfWidgetForm
		*/
	protected function configure ($options = array(), $attributes = array())
	{
		$this->addOption ('editor', 'fck');
		$this->addOption ('css', false);

		parent::configure ($options, $attributes);
	}

	/**
		* @param  string $name        The element name
		* @param  string $value       The value displayed in this widget
		* @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
		* @param  array  $errors      An array of errors for the field
		*
		* @return string An HTML tag string
		*
		* @see sfWidgetForm
		*/
	public function render ($name, $value = null, $attributes = array(), $errors = array())
	{
		$editorClass = 'sfRichTextEditorFCK';
		if (!class_exists ($editorClass)) {
			throw new sfConfigurationException (sprintf ('The rich text editor "%s" does not exist.', $editorClass));
		}

		$editor = new $editorClass ();
		if (!in_array ('sfRichTextEditor', class_parents ($editor))) {
			throw new sfConfigurationException (sprintf ('The editor "%s" must extend sfRichTextEditor.', $editor));
		}

		$attributes = array_merge ($attributes, $this->getOptions ());
		$editor->initialize ($name, $value, $attributes);
		return $editor->toHTML ();
	}
}
?>