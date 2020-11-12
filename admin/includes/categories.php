<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');

class TMSCategories
{
	public static function loadCategories($includecounts=false,$filters=array())
    {
		
		$keywords='';
		$templatetype='';
		$blacklist='';
		
		if(isset($filters['keywords']))
			$keywords=$filters['keywords'];
			
		if(isset($filters['templatetype']))
			$templatetype=$filters['templatetype'];
			
		if(isset($filters['blacklist']))
			$blacklist=$filters['blacklist'];
			
			
		
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
				
				if($includecounts)
				{
					$where=array();
					$where[]='INSTR(templates.categories,CONCAT(",",#__templateshop_categories.id,","))';
                
					if($keywords!="")
						$where[]='(INSTR(`keywords`,'.$db->Quote($keywords).') OR INSTR(templates.description,'.$db->Quote($keywords).'))';
                
				    if($templatetype!="")
						$where[]='`type`='.$db->Quote($templatetype);
                                
					if($blacklist!='')
						$where[]='!(templates.id IN ('.$blacklist.'))';
					
					$where[]='templates.description!=""';
                
                
					$s1='(SELECT COUNT(templates.id) FROM #__templateshop_templates AS templates WHERE '.implode(' AND ',$where).')';
					
					$s2='CONCAT(categoryname," (",'.$s1.',")") AS categoryname';
					$query->select('id,'.$s2);
					
					
					$query->where($s1.'>0');
					
				}
				else
					$query->select('id,categoryname');
					
                $query->from('#__templateshop_categories');
				$query->order('categoryname');
				
                $db->setQuery((string)$query);
                $records = $db->loadObjectList();
                
                return $records;
        }
		
	public static function getCategories()
        {
                $login=TMSMisc::getSettingValue('login');
                $webapipassword=TMSMisc::getSettingValue('webapipassword');
				$credentialsok=(int)TMSMisc::getSettingValue('credentialsok');
                
                if($login=='')
				{
					if($credentialsok==1)
					TMSMisc::setSettingValue('credentialsok',0);
					
                        return 'Login not set';
				}
                
                if($webapipassword=='')
				{
					if($credentialsok==1)
					TMSMisc::setSettingValue('credentialsok',0);
				  
                        return 'Web API Password not set';
				}
                

                $url = 'http://www.templatemonster.com/webapi/categories.php?login='.$login.'&webapipassword='.$webapipassword.'&linebreak=*&delim=|';
			
				$htmlcode=TMSMisc::getURLData($url);
				
				if(strpos($htmlcode,'|')===false)
                {
                 if($credentialsok==1 and $htmlcode!='')
                  TMSMisc::setSettingValue('credentialsok',0);

                  return "Template Monster Server response: ".$htmlcode;
                }     
                      
                if($credentialsok==0)
                 TMSMisc::setSettingValue('credentialsok',1);
				 
                
                $db = JFactory::getDBO();
                
                $query=array();
                $records=explode('*',$htmlcode);
                foreach($records as $rec)
                {
                        $pair=explode('|',$rec);
                        if(count($pair)==2)
                        {
				
				$query='INSERT INTO #__templateshop_categories (id, categoryname) '
					.'SELECT * FROM (SELECT '.$db->Quote($pair[0]).', '.$db->Quote($pair[1]).') AS tmp '
					.'WHERE NOT EXISTS ('
						.'SELECT id FROM #__templateshop_categories WHERE id='.$db->Quote($pair[0])
					.') LIMIT 1;';

					
                                $db->setQuery($query);
                                if (!$db->query())    die ( $db->stderr());
                        }
                        
                }
				
				TMSMisc::setSettingValue('lastcategoriesupdate',time());
                
				$categories=TMSCategories::loadCategories(true,array());
				
                return count($categories);
        }
}
?>