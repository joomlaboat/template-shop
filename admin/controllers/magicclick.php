<?php
/**
* Place Article Joomla! Plugin
*
* @version	2.2.1
* @author    Ivan Komlev
* @copyright Copyright (C) 2012-2018 Ivan Komlev. All rights reserved.
* @license	 GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.plugin.plugin');

class plgSystemMagicClick extends JPlugin
{

	public function onBeforeRender()
	{
		$jinput=JFactory::getApplication()->input;
		$task=$jinput->getCmd('magicclick_task');
		if($task!='')
		{
			plgSystemMagicClick::process_api_task($task);
		}
		else
		{
			$app = JFactory::getApplication();
			if($app->isSite())
				plgSystemMagicClick::setCSSStyleHeader();
		}
	}

	protected function process_api_task($task)
	{
		if($task=='find')
		{
			$jinput=JFactory::getApplication()->input;
			$tag=$jinput->getCmd('tag');
			$Itemid=$jinput->getInt('Itemid');
			$url=base64_decode($jinput->getString('url','', 'BASE64'));
			$content=base64_decode($jinput->get('content','', 'BASE64'));

			process_drivers($tag,$itemid,$url,$content);
		}

	}

	protected function process_drivers($tag,$itemid,$url,$content)
	{
echo 'ssssssss';
die;
		$path=JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'magicclick'.DIRECTORY_SEPARATOR.'drivers';

		$results=array();
		$pluginfiles = scandir($path);
		foreach($pluginfiles as $pluginfile)
		{
			if($pluginfile!='.' and $pluginfile!='..')
			{
				$filename=$path.DIRECTORY_SEPARATOR.$pluginfile;
				if(strpos($filename,'.htm')===false and file_exists($filename))
				{
				    require_once($filename);
				    $functionname='magicclick_check_'.str_replace('.php','',$pluginfile);
					echo 'running plugin: "'.$functionname.'"<br/>';
					$result=call_user_func($functionname,$row,$table);

					if($result!='')
						$results[]=$result;

				}
			}

			if (ob_get_contents())
            ob_end_clean();

            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Content-Type: application/json; charset=utf-8');
            header("Pragma: no-cache");
            header("Expires: 0");

            echo json_encode($results);
            die;
		}
	}


	static public function setCSSStyleHeader()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/plugins/system/magicclick/magicclick.css');
		$document->addScript(JURI::root(true).'/plugins/system/magicclick/magicclick.js');

		$jinput=JFactory::getApplication()->input;
		$Itemid=$jinput->getInt('Itemid');

		$js='
		<script>
			var MagicClick_Itemid='.$Itemid.';
		</script>
		';

		$document->addCustomTag($js);

	}

	public function onAfterRender()
	{
		$app = JFactory::getApplication();

		if($app->isSite())
		{
			//$output = JResponse::getBody();
			//$output=plgSystemMagicClick::addDivs($output);
			//JResponse::setBody($output);

		}
	}
/*
	static public function addDivs($content)
	{
		$content=str_ireplace('</div>','</div></DIV>',$content);
		$content=str_ireplace('<div ','<DIV class="magicclick"><DIV ',$content);

		$content=str_ireplace('</p>','</p></DIV>',$content);
		$content=str_replace('<p ','<DIV class="magicclick"><p ',$content);
/*
		$options=array();
		$fList=plgContentMagicClick::getListToReplace('div',$options,$new_content,'< ');

		$i=0;
		foreach($fList as $fItem)
		{
			//$id='magicclick_'.$i; id="'.$id.'"
            $vlu='<div class="magicclick"><div ';

			$new_content=str_replace($fItem,$vlu,$new_content);
			$i++;
		}


*/
/*
return $content;
	}*/


/*
	static public function getListToReplace($par,&$options,&$text,$qtype,$separator=':',$quote_char='"')
	{
		$fList=array();
		$l=strlen($par)+2;

		$offset=0;
		do{
			if($offset>=strlen($text))
				break;

			$ps=strpos($text, $qtype[0].$par.$separator, $offset);
			if($ps===false)
				break;


			if($ps+$l>=strlen($text))
				break;

			$quote_open=false;

			$ps1=$ps+$l;
			$count=0;
			do{

				$count++;
				if($count>100)
					die;

				if($quote_char=='')
					$peq=false;
				else
				{
					do
					{
						$peq=strpos($text, $quote_char, $ps1);

						if($peq>0 and $text[$peq-1]=='\\')
						{
							// ignore quote in this case
							$ps1++;

						}
						else
							break;

					}while(1==1);
				}

				$pe=strpos($text, $qtype[1], $ps1);

				if($pe===false)
					break;

				if($peq!==false and $peq<$pe)
				{
					//quote before the end character

					if(!$quote_open)
						$quote_open=true;
					else
						$quote_open=false;

					$ps1=$peq+1;
				}
				else
				{
					if(!$quote_open)
						break;

					$ps1=$pe+1;

				}
			}while(1==1);



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
	*/
}
