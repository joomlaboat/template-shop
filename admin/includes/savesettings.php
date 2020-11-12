<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


	function makeQueryLine($field,$value)
	{
		$db = JFactory::getDBO();
		
		
		return 'INSERT INTO #__templateshop_settings (`option`,`value`)
		VALUES ('.$db->Quote($field).', '.$db->Quote($value).')
		ON DUPLICATE KEY UPDATE `value`='.$db->Quote($value);
										
		/*
		return 'INSERT INTO #__templateshop_settings (`option`, `value`) '
					.'SELECT * FROM (SELECT '.$db->Quote($field).', '.$db->Quote($value).') AS tmp '
					.'WHERE NOT EXISTS ('
						.'SELECT `option` FROM #__templateshop_settings WHERE `option`='.$db->Quote($field)
					.') LIMIT 1;';
					*/
	}
        

        function do_saveSettings()
        {
			$jinput = JFactory::getApplication()->input;
			
			//https://secure.mytemplatestorage.com/personal/webapi.php
			$login=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", $jinput->getString('login','')));
			$webapipassword=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", $jinput->getString('webapipassword','')));
			$presetcode=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", $jinput->getString('presetcode','')));
			
			$imagefolder_a = $jinput->getArray(array(
				'jform' => array('imagefolder' => 'PATH')
			));
			
			$imagefolder=$imagefolder_a['jform']['imagefolder'];
						echo $imagefolder;
			$db = JFactory::getDBO();
			$query=array();
			$query[] = makeQueryLine('login',$login);
			$query[] = makeQueryLine('webapipassword',$webapipassword);
			$query[] = makeQueryLine('presetcode',$presetcode);
			
			$query[] = makeQueryLine('imagefolder',$imagefolder);
			
			foreach($query as $q)
			{
				
				$db->setQuery($q);
				if (!$db->query())    die ( $db->stderr());
			}
			
			
			require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'categories.php');
			TMSCategories::getCategories();
		
			return true;

        }
		

?>