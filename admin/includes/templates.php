<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
 require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');

class TMSTemplates
{
 
 
        public static function loadTemplates($lastadded,$filter,$keywords,$templatetype,$categories,$sortby,$order,$currency,$blacklist,$limit,&$total_templates)
        {
          $categories_array=explode(',',$categories);
          $categoryid=(int)$categories[0];
         
            $lastupdate=(int)TMSMisc::getSettingValue('lasttemplatesupdate');
            $t=time();
            
            if($t<$lastupdate+36000)//10h
            {
             $templates=TMSTemplates::getTemplatesFromDB($lastadded,$filter,$keywords,$templatetype,$categories,$sortby,$order,$currency,$blacklist,$limit,$total_templates);
            }
            else
             $templates=array();
             
             
             if(count($templates)==0)
             {
              for($i=1;$i<4;$i++)//on fresh installations it may require to load more templates to finaly get these that fit search filter. Update template in settings to 100%.
              {
                $wasitpossible=TMSTemplates::updateIncompleteTemplates($keywords,$categoryid);
                if(!$wasitpossible)
                 return array();
               
               
             
                TMSTemplates::getTemplatesFromAPI($lastadded,$filter,$keywords,$categoryid,$sortby,$order,$currency);
                $templates=TMSTemplates::getTemplatesFromDB($lastadded,$filter,$keywords,$templatetype,$categories,$sortby,$order,$currency,$blacklist,$limit,$total_templates);
                if(count($templates)>0)
                 break;
               }
               
               TMSMisc::setSettingValue('lasttemplatesupdate',time());
             }
            
            
          
            return $templates;
        }
 
        public static function getTemplatesFromDB($lastadded,$filter,$keywords,$templatetype,$categories,$sortby,$order,$currency,$blacklist,$limit,&$total_templates)
        {
                $categories=explode(',',$categories);
         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__templateshop_templates');
                
                $where=array();
                
                $wcat=array();
                foreach($categories as $cat)
                {
                 if((int)$cat!=0)
                 {
                  $wcat[]='INSTR(categories,'.$db->Quote(','.$cat.',').')';
                 }
                }
                if(count($wcat)>0)
                 $where[]='('.implode(' OR ',$wcat).')';
                
                if($keywords!="")
                {
                  $where[]='(INSTR(`keywords`,'.$db->Quote($keywords).') OR INSTR(`description`,'.$db->Quote($keywords).'))';
                }
                
                if($templatetype!="")
                {
                  $where[]='`type`='.$db->Quote($templatetype);
                }
                
                if($blacklist!='')
                 $where[]='!(id IN ('.$blacklist.'))';
                 
                
                $where[]='description!=""';
                
                
                $query->where(implode(' AND ',$where));
                
                $db->setQuery((string)$query);
		$db->execute()
                $total_templates=$db->getNumRows();
                
                $jinput = JFactory::getApplication()->input;
                $currentpage=$jinput->getInt('tmspage',1);
                
                $pos=($currentpage-1)*$limit;
                
                $db->setQuery((string)$query,$pos,$limit);
                $records = $db->loadObjectList();

                
                return $records;
        }
        
        public static function getTemplateDB_ID_MIN_MAX($category)
        {
         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('MIN(id) AS min_id, MAX(id) AS max_id');
                $query->where('category='.$category);
                $query->from('#__templateshop_templates');
                
                $db->setQuery((string)$query,0,1);
                $records = $db->loadObjectList();
                
                if(count($records)==0)
                {
                 //something wrong
                 return [0,0];
                }
                
                $rec=$records[0];
                $minmax=[(int)$rec->min_id,(int)$rec->max_id];
                return $minmax;
        }
        
        public static function getTemplatesFromAPI($lastadded,$filter,$keywords,$category,$sortby,$order,$currency)
        {
         $minmax=TMSTemplates::getTemplateDB_ID_MIN_MAX($category);
         
         if($minmax[0]==0 and $minmax[1]==0)
         {
          $from=0;
          $to=0;
         }
         else
         {
          $step=1000;
          $from=$minmax[1]+1;
          $to=$from+$step;
         }
          $count=TMSTemplates::getTemplatesFromAPIFromTo($lastadded,$filter,$keywords,$category,$sortby,$order,$currency,$from,$to);

        }
        
        public static function getTemplatesFromAPIFromTo($lastadded,$filter,$keywords,$category,$sortby,$order,$currency,$from,$to)
        {

            
                $login=TMSMisc::getSettingValue('login');
                $webapipassword=TMSMisc::getSettingValue('webapipassword');
                $credentialsok=(int)TMSMisc::getSettingValue('credentialsok');
                
                if($login=='')
                { if($credentialsok==1)
                   TMSMisc::setSettingValue('credentialsok',0);
                        return 'Login not set';
                }
                
                if($webapipassword=='')
                {
                 if($credentialsok==1)
                  TMSMisc::setSettingValue('credentialsok',0);
                        return 'Web API Password not set';
                }
                
    
                $url = 'http://www.templatemonster.com/webapi/templates_screenshots4.php?login='.$login.'&webapipassword='.$webapipassword.'&linebreak=*&delim=|';
                
                $params=array();
                if($from>1)
                 $params[]='from='.$from;
                 
                if($to!=0)
                 $params[]='to='.$to;
                
                if($lastadded!=0)
                    $params[]='last_added='.$lastadded;
                    
                $params[]='filter='.$filter;
                
                if((int)$category!=0)
                $params[]='category='.(int)$category;
                
                if($sortby=='0')
                    $params[]='sort_by=number';
                elseif($sortby=='1')
                    $params[]='sort_by=date';
                else
                    $params[]='sort_by=price';
                
                if($sortby=='0')
                    $params[]='order=asc';
                else
                    $params[]='order=desc';
                
                $cur=array('USD','EUR','CAD','GBP','JPY','AUD');
                if($currency>0 and $currency<=4)     //USD is 0 - Default, no need to provide the currency code
                    $params[]='currency='.$cur[(int)$currency];
                    
                $params[]='list_delim=,';
                $params[]='list_begin=$';
                $params[]='list_end=$';
                $params[]='full_path=true';
                
                $url.='&'.implode('&',$params);
                $htmlcode=TMSMisc::getURLData($url);

                if(strpos($htmlcode,'|')===false)
                {
                 if($credentialsok==1 and $htmlcode!='')
                  TMSMisc::setSettingValue('credentialsok',0);

                  return 0;
                }     
                      
                if($credentialsok==0)
                 TMSMisc::setSettingValue('credentialsok',1);
                                          
                $records=explode('*',$htmlcode);
                
                
                foreach($records as $record)
                {
                    TMSTemplates::saveApiRecord($record,$category,$keywords,$filter);
                }

        }
        
        public static function saveApiRecord($record,$category,$keywords,$filter)
        {
            $pair=explode('|',$record);
            
            if(count($pair)<16)
             return false;
            
            $id=(int)$pair[0];
            
            $t=TMSTemplates::loadTemplate($id);
            if($t!=null)
             return false;
            
            $db = JFactory::getDBO();
            $sets=array();
            
            $sets[]='id='.$id;
            $sets[]='price='.$db->Quote($pair[1]);
            $sets[]='exclusiveprice='.$db->Quote($pair[2]);
            
            $sets[]='dateofaddition='.$db->Quote($pair[3]);
            $sets[]='numberofdownloads='.$db->Quote($pair[4]);
            $sets[]='ishosting='.$db->Quote($pair[5]);
            $sets[]='isflash='.$db->Quote($pair[6]);
            $sets[]='isadult='.$db->Quote($pair[7]);
            $sets[]='isuniquelogo='.$db->Quote($pair[8]);
            $sets[]='isnonuniquelogo='.$db->Quote($pair[9]);
            $sets[]='isuniquecorporate='.$db->Quote($pair[10]);
            $sets[]='isnonuniquecorporate='.$db->Quote($pair[11]);
            $sets[]='author='.$db->Quote($pair[12]);
            $sets[]='isfull='.$db->Quote($pair[13]);
            $sets[]='numberofpages='.$db->Quote($pair[14]);
            
            $scr=str_replace('$','',$pair[15]);
            
            $sets[]='screenshots='.$db->Quote($scr);
            
            $sets[]='category='.(int)$category;
            $sets[]='keywords='.$db->Quote($keywords);
            $sets[]='filter='.(int)$filter;
          
            $query='INSERT #__templateshop_templates SET '.implode(',',$sets).';';
            $db->setQuery($query);
		$db->execute();
        }
        
        
        public static function loadTemplate($id)
        {
         		$db = JFactory::getDBO();
           $query = $db->getQuery(true);
           $query->select('*');
           $query->from('#__templateshop_templates');
           $query->where('id='.$id);
				
           $db->setQuery($query,0,1);
           $records = $db->loadObjectList();
				
           if(count($records)==0)
           	return null;
                
           return $records[0];
        }
        
        
        
        public static function getTemplatesCounts()
        {
         		$db = JFactory::getDBO();
           $query = $db->getQuery(true);
           $query->select('(SELECT COUNT(id) FROM #__templateshop_templates WHERE description!="") AS completedtemplates, COUNT(id) AS totaltemplates');
           $query->from('#__templateshop_templates');
				
           $db->setQuery($query,0,1);
           $records = $db->loadObjectList();
				
           if(count($records)==0)
            return [-1,-1];
    
           $rec=$records[0];
           return [$rec->completedtemplates,$rec->totaltemplates];
           
        }
        
        
        public static function updateIncompleteTemplates($keywords='',$category=null)
        {
         
          $incompletetemplates=TMSTemplates::getIncompleteTemplates($keywords,$category);

          foreach($incompletetemplates as $t)
          {
           $templateDetails=TMSTemplates::getTemplateDetaislFromAPI($t->id);
           if(is_array($templateDetails) and count($templateDetails)>21)
           {
            
            $screenshots=$templateDetails[16];
            $keywords=$templateDetails[17];
            $categories=$templateDetails[18];
            $type=str_replace(',','',$templateDetails[21]);
            $description=$templateDetails[22];
            $pages=$templateDetails[23];
           }
           elseif($templateDetails=='')
           {
            $screenshots='';
            $keywords='';
            $categories='';
            $type='';
            $description='no data';
            $pages='';
            
           }
           else
           {
            return false;
            
           }
           TMSTemplates::updateTemplateRecord($t->id,$screenshots,$keywords,$categories,$type,$description,$pages);
           
          
          }
          
          return true;
          
        }
        
        
        public static function updateTemplateRecord($template_number,$screenshots,$keywords,$categories,$type,$description,$pages)
        {
            $db = JFactory::getDBO();
            $sets=array();
            
            $sets[]='id='.$template_number;
            $sets[]='screenshots='.$db->Quote($screenshots);
            $sets[]='keywords='.$db->Quote($keywords);
            $sets[]='categories='.$db->Quote($categories);
            $sets[]='type='.$db->Quote($type);
            $sets[]='description='.$db->Quote($description);
            $sets[]='pages='.$db->Quote($pages);
            
            
          
            $query='UPDATE #__templateshop_templates SET '.implode(',',$sets).' WHERE id='.$template_number.';';
            
            $db->setQuery($query);
		$db->execute();
        }
        
        public static function getIncompleteTemplates($keywords='',$category=null)
        {
         
         		$db = JFactory::getDBO();
           $query = $db->getQuery(true);
           $query->select('id,category');
           $query->from('#__templateshop_templates');
           
           $where=array();
           $where[]='description=""';
           
           if($category!=null)
            $where[]='category='.$category;
                
           $query->where(implode(' AND ',$where));
           //if($keywords!="")
            //$where[]='(INSTR(`type`,'.$db->Quote($keywords).') OR INSTR(`keywords`,'.$db->Quote($keywords).'))';
           
           $db->setQuery($query,0,10);
           $records = $db->loadObjectList();
           
				
           return $records;
        }
        
        
        
        
        public static function getTemplateDetaislFromAPI($template_number)
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
                
    
                $url = 'http://www.templatemonster.com/webapi/template_info3.php?login='.$login.'&webapipassword='.$webapipassword.'&linebreak=*&delim=|';
                $params=array();
                
                
                $params[]='template_number='.$template_number;
                
                $params[]='list_delim=,';
                $params[]='list_begin=,';
                $params[]='list_end=,';
                
                
                $url.='&'.implode('&',$params);
   
        
   
                $htmlcode=TMSMisc::getURLData($url);

                if(strpos($htmlcode,'|')===false)
                {
                 
                 if($credentialsok==1 and $htmlcode!='')
                  TMSMisc::setSettingValue('credentialsok',0);
                  
                  return $htmlcode;
                }     
                      
                if($credentialsok==0)
                 TMSMisc::setSettingValue('credentialsok',1);  
                      
                $lines=explode('*',$htmlcode);
                $line=$lines[0];
                
                return explode('|',$line);
                
        }
        
}      
 
?>