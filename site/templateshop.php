<?php
/**
 * TEMPLATESHOP Joomla! Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by Template Shop

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
    $controller = JControllerLegacy::getInstance('TEMPLATESHOP');
}
else
{
    $controller = JController::getInstance('TEMPLATESHOP');
}


 
// Perform the Request task
$jinput = JFactory::getApplication()->input;
$controller->execute($jinput->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
