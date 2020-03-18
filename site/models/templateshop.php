<?php
/**
 * TEMPLATESHOP Joomla! Native Component
 * @version 1.1.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
jimport('joomla.application.menu' );

JHTML::addIncludePath(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'helpers');
/**
 * TEMPLATESHOP Model
 */
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'layoutrenderer.php');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'pagination.php');
 
class TEMPLATESHOPModelTEMPLATESHOP extends JModelItem
{
		var $templateshopcode=null;
	
        /**
         * Get the message
         * @return actual youtube galley code
         */
        public function getTEMPLATESHOPCode() 
        {
			if($this->templateshopcode!=null)
				return $this->templateshopcode;

			$result='';
			
			$app	= JFactory::getApplication();
			$params	= $app->getParams();

			$presetid=(int)$params->get( 'presetid' );
			
			$keywords=$params->get( 'keywords' );
			
			$templatetype=$params->get( 'templatetype' );
			
			$jinput = JFactory::getApplication()->input;
			$categories = $jinput->getString('tmscategory','');
			if($categories=='')
			{
				$categories=(int)$params->get( 'category' );
				$categoryid=(int)$params->get( 'category' );
				
			}
			else
				$categoryid=$categories[0];
			
			
			$blacklist=$params->get( 'blacklist' );
								
			$categoryname='Categories loaded.';
                        
                        if($presetid!=0)
                        {
							$preset=$this->loadPreset($presetid);
							if($preset==null)
							{
								$this->templateshopcode='<p>Template Shop: Selected Preset not exists.</p>';

							}
							else
							{
								
								$keywords=$params->get( 'keywords' );
								if($keywords=='')
									$keywords=$preset->keywords;
								
								if($templatetype=='')
									$templatetype=$preset->templatetype;					
							
								require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'templates.php');
								
								if($categoryid!=0)
								{
									//Replace preset category
									
									$category=$this->loadCategory($categoryid);
									
									if($category!=null)
									{
										//$preset->category=$categoryid;
										$categoryname=$category->categoryname;
									}
								}
								else
									$categoryname='Category not selected';
							
								$result=$this->ApplyPageLayoutTags($preset,$categoryname,$keywords,$templatetype,$blacklist,$categories);
								


								if($params->get( 'allowcontentplugins' ))
								{
								
									$o = new stdClass();
									$o->text=$result;
							
									$dispatcher	= JDispatcher::getInstance();
							
									JPluginHelper::importPlugin('content');
							
									$r = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$o, &$params_, 0));
							
									$result=$o->text;
								}
				
								$this->templateshopcode=$result;
								
							}

                        }
						else
							$this->templateshopcode='<p>Template Shop: Preset not selected.</p>';
						
				
				
                return $this->templateshopcode;
        }
		
		
		
		function loadPreset($id)
		{
				$db = JFactory::getDBO();
                $query = $db->getQuery(true);
				
				//$s1='(SELECT categoryname FROM #__templateshop_categories WHERE #__templateshop_categories.id=#__templateshop_presets.category) AS categoryname';
                $query->select('*');//,'.$s1);
                $query->from('#__templateshop_presets');
				$query->where('id='.$id);
				
                $db->setQuery($query,0,1);
                $records = $db->loadObjectList();
				
				if(count($records)==0)
					return null;
                
                return $records[0];
		}
		
		
		function loadCategory($id)
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,categoryname');
                $query->from('#__templateshop_categories');
				$query->where('id='.$id);
                $db->setQuery((string)$query,0,1);
                $records = $db->loadObjectList();
                
                if(count($records)==0)
					return null;
                
                return $records[0];
        }
		
		

		function ApplyPageLayoutTags(&$preset,$categoryname,$keywords,$templatetype,$blacklist,$categories)
		{
			$result=$preset->layout;
			
			$tags=array(['keywords',$keywords],['filter',$preset->filter],['category',$categoryname]);
			foreach($tags as $tag)
				$result=str_replace('['.$tag[0].']',$tag[1],$result);
			
			$limit=5; //default limit
			
			$tags=array('templates','pagination','categoryselector');
			
			foreach($tags as $tag)
			{
				$ValueOptions=array();
				$ValueList=TEMPLATESHOPLayoutRenderer::getListToReplace($tag,$ValueOptions,$result,'[]');
				
				$i=0;
				foreach($ValueList as $ValueListItem)
				{
					$options=$ValueOptions[$i];
					$value='';
					switch($tag)
					{
						case 'templates':
					
							$limit=(int)$options;
							$total_templates=0;
							$templates=TMSTemplates::loadTemplates($preset->lastadded,$preset->filter,$keywords,$templatetype,$categories,$preset->sortby,$preset->orderdirection,$preset->currency,$blacklist,$limit,$total_templates);
							
							if(is_array($templates))
								$str=$this->ApplyItemLayoutTags($preset,$templates);
							else
								$str='<p style="background-color:red;color:white;">Could not get any templates. Please check Template Shop component settings.</p>';
							
							$value='<div id="templates_wrapper_'.$preset->id.'" class="tms_wrapper">'.$str.'</div>';
						
						break;
					
						case 'categoryselector':
				
							$value=$this->getCategorySelector($keywords,$templatetype,$blacklist);
							
						break;
					
						case 'pagination':
							$numerofpagesperbar=(int)$options;
							$value=TMSPagination::getPagination($limit,$numerofpagesperbar,$total_templates,$preset->id);
					
						break;
					
			
					}
					
					$result=str_replace($ValueListItem,$value,$result);
					
					$i++;
				}
			}
			
			return '<div id="tms_wrapper_'.$preset->id.'">'.$result.'</div>';
		}
		
		function getCategorySelector($keywords,$templatetype,$blacklist)
		{
			$control_name='tmsCategorySelecter';
			
			$jinput = JFactory::getApplication()->input;
			$categoryid = $jinput->getInt('tmscategory',0);
			
			$filters=['keywords'=>$keywords,
					  'templatetype'=>$templatetype,
					  'blacklist'=>$blacklist
					  ];
			
			$result=JHTML::_('TMSCategoryPublic.render',$control_name, $categoryid,'', $filters);
			
			return $result;
			
		}
		
		
		function ApplyItemLayoutTags(&$preset,&$templates)
		{
			$result='';
			
			foreach($templates as $template)
			{
				$result.=TEMPLATESHOPLayoutRenderer::render($preset->itemlayout,$template);
			}
			
			return $result;
		}
		
}
