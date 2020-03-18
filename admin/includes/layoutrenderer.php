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

jimport('joomla.filesystem.file');	
require_once('misc.php');
require_once('screenshots.php');

class TEMPLATESHOPLayoutRenderer
{
	public static function getValue($imagefolder,$fld,$option, &$template)
	{
		$fields_integer=array('id','numberofdownloads','ishosting','isflash','isadult','isuniquelogo','numberofpages','isfull','isnonuniquecorporate','isuniquecorporate','isnonuniquelogo');
		
		if(in_array($fld,$fields_integer))
		{
			$row_array = get_object_vars($template);
			
			return (int)$row_array[$fld];
		}
		
		$fields_float=array('price','exclusiveprice');
		
		if(in_array($fld,$fields_float))
		{
			$row_array = get_object_vars($template);
			
			return number_format((float)$row_array[$fld], 2);
		}
		
		
		switch($fld)
		{
			case 'dateofaddition':
				
				return $template->dateofaddition;
			
			case 'screenshots':
				
				return $template->screenshots;
				
				
				
			break;
		
			case 'screenshot':
				
				$params=explode(',',$option);
				
				return TEMPLATESHOPScreenshots::getCachedScreenShotFile($template->id,$imagefolder,$template->screenshots,$params);

				
			break;
		
			case 'settings':
				
				if($option=='login')
					return TMSMisc::getSettingValue('login');
				elseif($option=='webapipassword')
					return TMSMisc::getSettingValue('webapipassword');
				elseif($option=='presetcode')
					return TMSMisc::getSettingValue('presetcode');
				
				return '"'.$option.'" - wrong parameter.';
				
			break;
		
			case 'author':
				return $preset_row->$template->author;
			
			break;
		
			case 'isadmin':
				return TEMPLATESHOPLayoutRenderer::CheckAuthorization();
			break;
			
			case 'type':
				
				return $template->type;
			
			break;
		
			case 'typeicon':
				
				$alias=TMSMisc::slugify($template->type);
				if($option=='filled')
				{
					$src='/components/com_templateshop/images/icons/'.$alias.'-filled.svg';
					$file=str_replace('/',DIRECTORY_SEPARATOR,$src);
					if(!file_exists(JPATH_SITE.$file))
					{
						$src='/components/com_templateshop/images/icons/'.$alias.'.svg';
						$file=str_replace('/',DIRECTORY_SEPARATOR,$src);
						if(!file_exists(JPATH_SITE.$file))
						{
							return $src;
						}
						
					}
					else
						return $src;
				}
				else
				{
					$src='/components/com_templateshop/images/icons/'.$alias.'.svg';
					$file=str_replace('/',DIRECTORY_SEPARATOR,$src);
					if(!file_exists(JPATH_SITE.$file))
					{
						return $src;
					}
				}
				
				
				return '';
			
			break;
		
		
		}//switch($fld)
		
		return '';
		
	}//function
	
	

	public static function isEmpty($fld, &$template)
	{
		$fields_noempty=array('id','dateofaddition','settings');
		
		if(in_array($fld,$fields_noempty))
			return false;
		
		$fields_integer=array('price','exclusiveprice','numberofdownloads','ishosting','isflash','isadult','isuniquelogo','numberofpages','isfull','isnonuniquecorporate','isuniquecorporate','isnonuniquelogo');
		
		if(in_array($fld,$fields_integer))
		{
			$row_array = get_object_vars($template);
			$vlu=$row_array[$fld];
			
			if((int)$vlu==0)
				return true;
			else
				return false;
		}

		switch($fld)
		{
			case 'type':
				if($template->type=='')
					return true;
				else
					return false;
			break;
		
			case 'typeicon':
				if($template->type=='')
					return true;
				else
					return false;
			break;
		
			case 'screenshot':
				if($template->screenshots=='')
					return true;
				else
					return false;
			break;
		
			case 'screenshots':
				if($template->screenshots=='')
					return true;
				else
					return false;
			break;
		
			case 'author':
				if($template->author=='')
					return true;
				else
					return false;
				
			break;
		
			case 'isadmin':
				return !TEMPLATESHOPLayoutRenderer::CheckAuthorization();
			
			break;
		}
		
		return true;

	}
	

	
	public static function render($htmlresult, &$template)
	{
		$imagefolder=TMSMisc::getSettingValue('imagefolder');
		
		$fields_all=array('id','price','exclusiveprice','dateofaddition','numberofdownloads','ishosting','isflash','isadult','isuniquelogo','screenshots','screenshot','numberofpages','isfull','author','isnonuniquecorporate','isuniquecorporate','isnonuniquelogo','isadmin','settings','type','typeicon');
		
		foreach($fields_all as $fld)
		{
			
			$isEmpty=TEMPLATESHOPLayoutRenderer::isEmpty($fld,$template);
						
			$ValueOptions=array();
			$ValueList=TEMPLATESHOPLayoutRenderer::getListToReplace($fld,$ValueOptions,$htmlresult,'[]');
		
			$ifname='[if:'.$fld.']';
			$endifname='[endif:'.$fld.']';
						
			if($isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
				
					if(!($startif_===false))
					{
						
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);
			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
							
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$vlu= TEMPLATESHOPLayoutRenderer::getValue($imagefolder,$fld,$ValueOption,$template);
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}// IF NOT
					
			$ifname='[ifnot:'.$fld.']';
			$endifname='[endifnot:'.$fld.']';
						
			if(!$isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
		
					if(!($startif_===false))
					{
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);

			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
				$vlu='';			
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}
	
		}//foreach($fields as $fld)
		
		return $htmlresult;
		
	}
	
	public static function getListToReplace($par,&$options,&$text,$qtype)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, $qtype[0].$par.':', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, $qtype[1], $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=trim(substr($text,$ps+$l,$pe-$ps-$l));
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		//for these with no parameters
		$ps=strpos($text, $qtype[0].$par.$qtype[1]);
		if(!($ps===false))
		{
			$options[]='';
			$fList[]=$qtype[0].$par.$qtype[1];
		}
		
		return $fList;
	}
	
	public static function getPagination($num,$limitstart,$limit,&$preset_row)
	{
		
				$AddAnchor=false;
				if($preset_row->openinnewwindow==2 or $preset_row->openinnewwindow==3)
				{
					$AddAnchor=true;
				}
				
					require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'pagination.php');
					
					$thispagination = new YGPagination($num, $limitstart, $limit, '', $AddAnchor );
				
				return $thispagination;
	}
	
	
	public static function deleteURLQueryOption($urlstr, $opt)
	{
		$url_first_part='';
		$p=strpos($urlstr,'?');
		if(!($p===false))
		{
			$url_first_part	= substr($urlstr,0,$p);
			$urlstr	= substr($urlstr,$p+1);
		}

		$params = array();
		
		$urlstr=str_replace('&amp;','&',$urlstr);
		
		$query=explode('&',$urlstr);
		
		$newquery=array();					

		for($q=0;$q<count($query);$q++)
		{
			$p=stripos($query[$q],$opt.'=');
			if($p===false or ($p!=0 and $p===false))
				$newquery[]=$query[$q];
		}
		
		if($url_first_part!='' and count($newquery)>0)
			$urlstr=$url_first_part.'?'.implode('&',$newquery);
		elseif($url_first_part!='' and count($newquery)==0)
			$urlstr=$url_first_part;
		else
			$urlstr=implode('&',$newquery);
		
		return $urlstr;
	}
	

	

	
	
	
	
	
	

	
	public static function getDescriptionByVideoID($videoid,&$gallery_list)
	{
		if(isset($gallery_list) and count($gallery_list)>0)
		{
				foreach($gallery_list as $g)
				{
						if($g['videoid']==$videoid)
								return $g['description'];
				}
		}
		
		return '';
	}
	
	

	
	

	public static function curPageURL($add_REQUEST_URI=true)
	{
		$pageURL = '';
		
			$pageURL .= 'http';
			
			if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			
			$pageURL .= "://";
			
			if (isset($_SERVER["HTTPS"]))
			{
				if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"];
				}
			}
			else
				$pageURL .= $_SERVER["SERVER_NAME"];
			
			if($add_REQUEST_URI)
			{
				//clean Facebook staff
				$uri=$_SERVER["REQUEST_URI"];
				if(!(strpos($uri,'fb_action_ids=')===false))
				{
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'fb_action_ids');
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'fb_action_types');
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'fb_source');
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'action_object_map');
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'action_type_map');
					$uri= TEMPLATESHOPLayoutRenderer::deleteURLQueryOption($uri, 'action_ref_map');
				}
				$pageURL .=$uri;
			}
		
		return $pageURL;
	}
	
	
	
	
	
		
	
	
	

	
	


	public static function CheckAuthorization()
	{
		$user = JFactory::getUser();
		
		$userid = (int)$user->get('id');
		if($userid==0)
			return false;
		
		
		$isroot = $user->authorise('core.admin');
		
		
		return (bool)$isroot;
		
	}

		









	

}

