<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat 
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		view.html.php
	@author			Ivan Komlev <http://joomlaboat.com>	
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Templateshop View class for the Settings
 */
class TemplateshopViewSettings extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get component params
		$this->params = JComponentHelper::getParams('com_templateshop');
		// get the application
		$this->app = JFactory::getApplication();
		// get the user object
		$this->user = JFactory::getUser();
		// get global action permissions
		$this->canDo = TemplateshopHelper::getActions('settings');
		// Initialise variables.
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// add the tool bar
			$this->addToolBar();
		}

		// set the document
		$this->setDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function setDocument()
	{

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new templateshopHeaderCheck;     
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/administrator/components/com_templateshop/assets/css/settings.css', (TemplateshopHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css'); 
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// hide the main menu
		$this->app->input->set('hidemainmenu', true);
		// set the title
		if (isset($this->item->name) && $this->item->name)
		{
			$title = $this->item->name;
		}
		// Check for empty title and add view name if param is set
		if (empty($title))
		{
			$title = JText::_('COM_TEMPLATESHOP_SETTINGS');
		}
		// add title to the page
		JToolbarHelper::title($title,'wrench');
		// add the back button
		// JToolBarHelper::custom('settings.back', 'undo-2', '', 'COM_TEMPLATESHOP_BACK', false);
		// add cpanel button


		JToolBarHelper::save('settings.SaveSettings', 'JTOOLBAR_SAVE');

		//JToolBarHelper::custom('settings.SaveSettings', 'save-new', '', 'COM_TEMPLATESHOP_SAVE', false);

		JToolBarHelper::cancel('settings.dashboard', 'JTOOLBAR_CLOSE');
		
		//JToolBarHelper::custom('settings.dashboard', 'grid-2', '', 'COM_TEMPLATESHOP_DASH', false);
		//if ($this->canDo->get('settings.save'))
		//{
			// add Save button.
			
		//}

		// set help url for this view if found
		$help_url = TemplateshopHelper::getHelpUrl('settings');
		if (TemplateshopHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_TEMPLATESHOP_HELP_MANAGER', false, $help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_templateshop');
		}
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		// use the helper htmlEscape method instead.
		return TemplateshopHelper::htmlEscape($var, $this->_charset);
	}
}
?>
