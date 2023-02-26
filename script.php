<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat 
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		script.php
	@author			Ivan Komlev <https://joomlaboat.com>	
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

/**
 * Script File of Template Shop Component
 */
class com_templateshopInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{

	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:support@joomlaboat.com">support@joomlaboat.com</a>.
		<br />We at Joomlaboat are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="http://joomlaboat.com" target="_blank">http://joomlaboat.com</a> today!</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// is redundant ...hmmm
		if ($type == 'uninstall')
		{
			return true;
		}
		// the default for both install and update
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.6.0'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.6.0 before continuing!', 'error');
			return false;
		}
		// do any updates needed
		if ($type == 'update')
		{
		}
		// do any install needed
		if ($type == 'install')
		{
		}
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// set the default component settings
		if ($type == 'install')
		{
			// Install the global extenstion params.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			// Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' . $db->quote('{"autorName":"Ivan Komlev","autorEmail":"support@joomlaboat.com"}'),
			);
			// Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_templateshop')
			);
			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();

			echo '<a target="_blank" href="http://joomlaboat.com" title="Template Shop">
				<img src="components/com_templateshop/assets/images/vdm-component.png"/>
				</a>';
		}
		// do any updates needed
		if ($type == 'update')
		{
			echo '<a target="_blank" href="http://joomlaboat.com" title="Template Shop">
				<img src="components/com_templateshop/assets/images/vdm-component.png"/>
				</a>
				<h3>Upgrade to Version 1.1.5 Was Successful! Let us know if anything is not working as expected.</h3>';
		}
		
		$this->addPresets();
//		$this->addTemplates();
	}
	
	function checkIfThereAreRecords($table)
	{
		$db = JFactory::getDBO();
		
        $query = $db->getQuery(true);
        $query->select('COUNT(id) as count');
        $query->from($table);
        $db->setQuery((string)$query,0,1);
        $records = $db->loadObjectList();
		if(count($records)==0)
			return true;// somthing wrong - don't continue
		
		$rec=$records[0];
		if($rec->count>0)
			return true;

		return false;
	}
	
	function addPresets()
	{

		if(!$this->checkIfThereAreRecords('#__templateshop_presets'))
		{
			
			$path=JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR;
			
			$query=file_get_contents('components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'_presetsdump.mysql.utf8.sql');
			
			$db = JFactory::getDBO();
                
		        $db->setQuery($query);
			$db->execute();
			
			echo '<p>Default preset added.</p>';
		}
	}
	
	function addTemplates()
	{

		if(!$this->checkIfThereAreRecords('#__templateshop_templates'))
		{
			
			$path=JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR;
			
			$d=file_get_contents('components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'_templatesdump.mysql.utf8.sql');
			$queries=explode('INSERT INTO `#__templateshop_templates`',$d);
			
			$db = JFactory::getDBO();

		        foreach($queries as $q)
			{	if($q!='')
				{
					$query='INSERT INTO `#__templateshop_templates`'.$q;
					$db->setQuery($query);
					$db->execute();
				}
			}
			
			echo '<p>Initial template data loaded.</p>';
		}
	}
}
