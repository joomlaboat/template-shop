<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class TMSMisc
{
	var $videolist_row;
	var $preset_row;
	
	
	
	public static function parse_query($var)
	{
		$arr  = array();
		
		 $var  = parse_url($var);
		 $varquery=$var['query'];

		 
		 if($varquery=='')
			return $arr;
		
		 $var  = html_entity_decode($varquery);
		 $var  = explode('&', $var);
		 

		foreach($var as $val)
		{
			$x          = explode('=', $val);
			$arr[$x[0]] = $x[1];
		}
		unset($val, $x, $var);
		return $arr;
	}
	
	
	function csv_explode($delim=',', $str, $enclose='"', $preserve=false)
	{
		$resArr = array();
		$n = 0;
		$expEncArr = explode($enclose, $str);
		foreach($expEncArr as $EncItem)
		{
			if($n++%2){
				array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
			}else{
				$expDelArr = explode($delim, $EncItem);
				array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			    $resArr = array_merge($resArr, $expDelArr);
			}
		}
	return $resArr;
	}
	
	
	function mysqlrealescapestring($inp)
	{
		
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
		    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	    }

	    return $inp;

    }	

	public static function getURLData($url)
	{
			$htmlcode='';
		
			if (function_exists('curl_init'))
			{
				$ch = curl_init();
				$timeout = 150;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$htmlcode = curl_exec($ch);
				curl_close($ch);
				
			}
			elseif (ini_get('allow_url_fopen') == true)
			{
				$htmlcode = file_get_contents($url);
			}
			else
			{
				echo '<p style="color:red;">Cannot load data, enable "allow_url_fopen" or install cURL<br/>
				<a href="http://joomlaboat.com/template-shop/f-a-q/why-i-see-allow-url-fopen-message" target="_blank">Here</a> is what to do.
				</p>
				';
			}

			return $htmlcode;
	}
	
	
	
	public static function CreateParamLine(&$settings)
	{
		$a=array();
		
		foreach($settings as $s)
		{
			if(isset($s[1]))
				$a[]=$s[0].'='.$s[1];
		}

		return implode('&amp;',$a);
	}
	
	
	public static function getSettingValue($option)
	{
				$db = JFactory::getDBO();
				
				$query = 'SELECT value FROM #__templateshop_settings WHERE `option`="'.$option.'" LIMIT 1';
				
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
					$values=$db->loadAssocList();
				
				if(count($values)==0)
								return "";
				$v=$values[0];

				return $v['value'];
	}
	
	public static function setSettingValue($option,$value)
	{
		$db = JFactory::getDBO();
		/*
		$query='INSERT INTO #__templateshop_settings (`option`, `value`) '
					.'SELECT * FROM (SELECT '.$db->Quote($option).', '.$db->Quote($value).') AS tmp '
					.'WHERE NOT EXISTS ('
						.'SELECT `option` FROM #__templateshop_settings WHERE `option`='.$db->Quote($option)
					.') LIMIT 1;';
		*/
		$query='INSERT INTO #__templateshop_settings (`option`,`value`)
		VALUES ('.$db->Quote($option).', '.$db->Quote($value).')
		ON DUPLICATE KEY UPDATE `value`='.$db->Quote($value);

		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());
	}
	
	
	public static function slugify($text)
	{
		//or use
		//JFilterOutput::stringURLSafe($this->alias);
		
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		
		
		//ini_set('mbstring.substitute_character', "none");
		//$text= mb_convert_encoding($text, 'UTF-8', 'UTF-8');
		// transliterate
		
		//$text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
		$text = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $text);
		//$text = iconv('utf-8', 'us-ascii//IGNORE//TRANSLIT', $text);
		//$text = iconv('utf-8', 'ISO-8859-1//TRANSLIT', $text);
		
		
		

		// trim
		$text = trim($text, '-');

		

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
			return '';

		return $text;
	}


	

	public static function html2txt($document)
	{ 
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
		); 
		$text = preg_replace($search, '', $document); 
		return $text; 
	}
	
	
	protected static function url_origin($s, $use_forwarded_host=false)
	{
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME']);
		return $protocol . '://' . $host . $port;
	}
	
	public static function full_url($s, $use_forwarded_host=false)
	{
	    return TMSMisc::url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}
	
	
	public static function curPageURL()
    {
		$pageURL = 'http';
		
		if(isset($_SERVER["HTTPS"]))
		{
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		
		
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
    }
	

	public static function deleteURLQueryOption($urlstr, $opt)
	{

		$params = array();

		$url_array=explode('?',$urlstr);
		if(count($url_array)==1)
			return $urlstr;
		
		$query_str=$url_array[1];
		$query=explode('&',$query_str);
		
		$newquery=array();					
											
		for($q=0;$q<count($query);$q++)
		{
			$p=strpos($query[$q],$opt.'=');
			if($p===false or ($p!=0 and $p===false))
				$newquery[]=$query[$q];
		}
		
		$newurl=$url_array[0];
		if(count($newquery)>0)
			$newurl.='?'.implode('&',$newquery);
		
		return $newurl;
	}


	function _is_curl_installed()
	{
	    if  (in_array  ('curl', get_loaded_extensions())) {
	        return true;
	    }
	    else {
	        return false;
	    }
	}


}


?>