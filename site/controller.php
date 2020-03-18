<?php
/**
 * TEMPLATESHOP Joomla! Native Component
 * @version 1.1.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * TEMPLATESHOP Component Controller
 */

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
    class TEMPLATESHOPController extends JControllerLegacy
    {
    }
}
else
{
    class TEMPLATESHOPController extends JController
    {
    }
}

?>