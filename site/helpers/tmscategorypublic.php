<?php
/**
 * templateshop Joomla! 3.0 Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'categories.php');

class JHTMLTMSCategoryPublic
{


	static public function render($control_name, $value,$attribute,$filters)
    {
		
		$options = array();
                
                $records=TMSCategories::loadCategories(true,$filters);
				
				
                if(count($records)==0)
                {
                        $respond=TMSCategories::getCategories();
                        if($respond!='')
                        {
                                //error
                                $options[] = JHtml::_('select.option', 0, $respond);
                                return $options;
                        }
                        
                        $records=TMSCategories::loadCategories(true,$filters);
                }
                
            
		$options=array_merge(array(array('id'=>'','categoryname'=>'- '.JText ::_( 'SELECT' ))),$records);
	
		$attributes=$attribute.' onChange="return TMSCategorySelectorChanged(event,this);"';
		
		
		$document = JFactory::getDocument();
		$url=TMSMisc::curPageURL();
		$url=TMSMisc::deleteURLQueryOption($url, 'tmscategory');
		if(strpos($url,'?')===false)
			$url.='?';
		else
			$url.='&';
		
		$script='
	<script>
		function TMSCategorySelectorChanged(e,obj)
		{
		    e.preventDefault();
			var url="'.$url.'";
			
			var id=obj.value;
			window.location.href = url+"tmscategory="+id;
			return false;
		}
	</script>
		
';
		$document->addCustomTag($script);
		
	
		
		return JHTML::_('select.genericlist', $options, $control_name, $attributes, 'id', 'categoryname', $value,$control_name);
		
    }

}
