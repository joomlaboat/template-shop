<?php
/**
 * Template Shop Joomla! Native Component
 * @version 1.1.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
	
function TemplateShopBuildRoute(&$query) {

       $segments = array();
       if(isset($query['view']))
       {
	      if (empty($query['Itemid'])) {
		     $segments[] = $query['view'];
	      }
              unset( $query['view'] );
       }
       
       if(isset($query['video']))
       {
	      $segments[] = $query['video'];
	      unset( $query['video'] ); 
       }
       elseif(isset($query['videoid']))
       {
	      require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
	      
	      $allowsef=TemplateShopMisc::getSettingValue('allowsef');
	      if($allowsef==1)
	      {
		     $videoid=$query['videoid'];
	      
		     $db = JFactory::getDBO();
		
		     $db->setQuery('SELECT `alias` FROM `#__templateshop_videos` WHERE `videoid`="'.$videoid.'" LIMIT 1');
		     if (!$db->query())    die ('yg router.php 1 err:'. $db->stderr());
		     $rows = $db->loadObjectList();
		     
		     if(count($rows)!=0)
		     {
			    $row=$rows[0];
			    if($row->alias!='')
		         	   $segments[] = $row->alias;
       
			    unset( $query['videoid'] ); 
		     }
	      }
       }
       return $segments;

}

function TemplateShopParseRoute($segments)
{
       $vars = array();
       $vars['view'] = 'templateshop';
  
       $sIndex=0;


       if(isset($segments[$sIndex]))
       {
	      $alias=str_replace(':','-',$segments[$sIndex]);
	      $alias=preg_replace('/[^a-zA-Z0-9-_]+/', '', $alias);
	   
	      $db = JFactory::getDBO();
	   	
	      $db->setQuery('SELECT `videoid` FROM `#__templateshop_videos` WHERE `alias`="'.$alias.'" LIMIT 1');
	      if (!$db->query())    die ('yg router.php 2 err:'. $db->stderr());
	   
	      $rows = $db->loadObjectList();
	   
	      if(count($rows)==0)
		     $vars['videoid'] = '';
	      else
	      {
			  $row=$rows[0];
			  $vars['videoid'] = $row->videoid;
	      }
	   
       }
       return $vars;
}

?>