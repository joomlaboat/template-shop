<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat 
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		settings.php
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

// import Joomla modelitem library
jimport('joomla.application.component.modeladmin');

/**
 * Templateshop Settings Model
 */
class TemplateshopModelSettings extends JModelAdmin
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_templateshop.settings';

        /**
	 * Model user data.
	 *
	 * @var        strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		// Get the item main id
		$id = $this->input->getInt('id', null);
		$this->setState('settings.id', $id);

		// Load the parameters.
		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		$imagefolder=TMSMisc::getSettingValue('imagefolder');
		$item=array('imagefolder'=>$imagefolder);
		
		return $item;

	}
	
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_templateshop.edit.settings.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	} 
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_templateshop.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		
		// Only load these values if no id is found
		if (0 == $id)
		{
			// Set redirected field name
			$redirectedField = $jinput->get('ref', null, 'STRING');
			// Set redirected field value
			$redirectedValue = $jinput->get('refid', 0, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
		}

		return $form;
	}

  
}
