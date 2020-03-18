<?php
/**
 * TEMPLATESHOP for Joomla!
 * @version 1.1.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');

class TMSTypes
{
	public static function loadTypes()
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('`type` AS templatetype');
				$query->where('`type`!="" AND `type`!=",,"');
                $query->from('#__templateshop_templates');
				$query->group('`type`');
				$query->order('`type` ASC');
                $db->setQuery((string)$query);
				if (!$db->query())    die( $db->stderr());
				
                $records = $db->loadObjectList();
                return $records;
        }
}
?>