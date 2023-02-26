<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat 
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		settings.php
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

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Templateshop Settings Controller
 */
class TemplateshopControllerSettings extends JControllerLegacy
{
	public function __construct($config)
	{
		parent::__construct($config);
	}

	public function dashboard()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_templateshop', false));
		return;
	}
	
	function RefreshCategories($key = null, $urlVar = null)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'categories.php');
		$reply=TMSCategories::getCategories();

		$jinput = JFactory::getApplication()->input;
		$clean=$jinput->getInt('clean',0);
		
		if($clean==1)
		{
			if((int)$reply>0)
			{
				$result=['status'=>'Updated','count'=>(int)$reply];
			}
			else
			{
				$result=['status'=>'error','msg'=>$reply];
			}
			
				echo json_encode($result);
				die;
		}
		else
		{
			$this->setRedirect(
			'index.php?option=com_templateshop&view=settings'
			,JText::_('COM_TEMPLATESHOP_SETTINGS_CATEGORIESREFRESHED')
			);
		}
	    return true;
	}
	
	
	function RefreshTemplates($key = null, $urlVar = null)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'templates.php');
		$wasitpossible=TMSTemplates::updateIncompleteTemplates();

		$jinput = JFactory::getApplication()->input;
		$clean=$jinput->getInt('clean',0);
		
		if($wasitpossible)
		{
			if($clean==1)
			{
				$templates_count=TMSTemplates::getTemplatesCounts();
				$result=['total'=>$templates_count[1],'completed'=>$templates_count[0]];
				echo json_encode($result);
				die;
			}
			else
			{
				$this->setRedirect(
				'index.php?option=com_templateshop&view=settings'
				,JText::_('COM_TEMPLATESHOP_SETTINGS_TEMPLATESREFRESHED')
				);
			}
			return true;
		}
		else
		{
			if($clean==1)
			{
				$result=['error'=>'could not refresh records'];
				echo json_encode($result);
				die;
			}
			else
			{
				$this->setRedirect(
				'index.php?option=com_templateshop&view=settings'
				,JText::_('COM_TEMPLATESHOP_SETTINGS_TEMPLATESNOTREFRESHED')
				,'error'
				);
			}
			return false;
		}
	}
	
	function createFolder($key = null, $urlVar = null)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		
		$newfoldername = $this->input->get('newfoldername', 'tms_cache','PATH');
		$path=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$newfoldername;
		
		jimport('joomla.filesystem.folder');
		if(JFolder::create($path))
		{
			TMSMisc::setSettingValue('imagefolder',$newfoldername);

			$this->setRedirect(
				'index.php?option=com_templateshop&view=settings'
				,JText::_('COM_TEMPLATESHOP_SETTINGS_CACHEFOLDERCREATED')
			);
		}
		else
		{
			$this->setRedirect('index.php?option=com_templateshop&view=settings');
		}
		
	    return true;
	}
	
	function SaveSettings($key = null, $urlVar = null)
	{
	    $this->task = 'save';
	    require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'savesettings.php');
	    do_saveSettings();

		
		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_templateshop', true
			)
			,JText::_('COM_TEMPLATESHOP_SETTINGS_SAVED')
		);
	    return true;
	}
}
