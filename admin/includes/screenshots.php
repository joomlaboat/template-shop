<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class TEMPLATESHOPScreenshots
{
    public static function checkIfScreenshotTypeIsAcceptable($link)
	{
		//tm1://	http://scr.templatemonster.com/
		//tm2://	http://scr.template-help.com/
		$positive_keywords=array('tm1://','tm2://','http://scr.template-help.com/','http://scr.templatemonster.com/');
		if(!TEMPLATESHOPScreenshots::checkPositive($positive_keywords,$link))
			return false;
		
		//link must include any this keywords
		$positive_keywords=array('.png','.jpg','.jpeg');
		if(!TEMPLATESHOPScreenshots::checkPositive($positive_keywords,$link))
			return false;
		
		//link cannot include any of this keywords
		$negative_keywords=array('banner.jpg','.js','-p.','-module','-rs.','-origin.','-origin-','_origin.','_origin_','responsive-layout',
								 
								 '-de_','-en_','-fr_','-ru_','-es_'
								 
								 );
		
		foreach($negative_keywords as $k)
		{
			if(strpos($link,$k)!==false)
				return false;
		}
		
		return true;
	}
	
	public static function checkPositive(&$a,$link)
	{
		foreach($a as $k)
		{
			if(strpos($link,$k)!==false)
				return true;
		}
		
		return false;
	}
	
	public static function getCachedScreenShotFile($templateid,$imagefolder,$links_str,$params)
	{
		//echo '$links_str='.$links_str.'<br>';
		
		
		$min_width=(int)$params[0];
		$min_height=0;
		
		if(isset($params[1]))
			$min_height=(int)$params[1];
			
			
		$path=JPATH_SITE.DIRECTORY_SEPARATOR;
		$ProcessImageSrc='';
		$ProcessImagePath='';
			
		if($min_width!=0 and $min_height!=0)
		{
			//Check if prepared image already exists
			$ProcessImageSrc=TEMPLATESHOPScreenshots::makeProcessImageSrc($templateid,$imagefolder,$min_width,$min_height);
			$ProcessImagePath=$path.str_replace('/',DIRECTORY_SEPARATOR,$ProcessImageSrc);
			if(file_exists($ProcessImagePath))
			{
				//Processes file found
                if($ProcessImageSrc=='')
                    return '';
                
                if($ProcessImageSrc[0]!='/')
                    return '/'.$ProcessImageSrc;
                
				return $ProcessImageSrc;
			}
		}
		
		//echo 't='.$templateid.' cached file not found<br/>';
		
		//Clean screenshot links
		$new_scrs=array();
		$scrs=explode(',',$links_str);
		foreach($scrs as $n)
		{
			if(TEMPLATESHOPScreenshots::checkIfScreenshotTypeIsAcceptable($n))
				$new_scrs[]=$n;
		}
				
		if(count($new_scrs)==0)
			return '';
		
		
		if($min_width==0 or $min_height==0)
		{
			//If dimentions not set return first screen shot
			$src=TEMPLATESHOPScreenshots::checkCachedScreenShotFile($imagefolder,$new_scrs[0],0,0,0);
			return $src;
		}
		
			
		$start_time=time();
		$best_image_link='';
		
		//for($proportions_index=0.1;$proportions_index<1;$proportions_index+=0.1)
		//{
		
		$proportions_index=0;
			
			foreach($new_scrs as $link)
			{
				//echo 'link='.$link.'<br/>';
				if(time()-$start_time>5)
				{
					//echo 'timeout - return any image<br/>';
					
					//timeout - return any image
					return TEMPLATESHOPScreenshots::checkCachedScreenShotFile($imagefolder,$link,0,0,$proportions_index);
				}
			
				$src=TEMPLATESHOPScreenshots::checkCachedScreenShotFile($imagefolder,$link,$min_width,$min_height,$proportions_index);
				if($src!='')
				{
					if($min_width==0 or $min_height==0)
						return $src;
				
				
					//echo 'confirmed image: '.$src.'<br/>';
					$best_image_link=$src;
					break;
				}
			}
			
			//if($best_image_link!='')
				//break;
		//}	
		//Image found
		
		//echo 'save best_image_link as ='.$ProcessImagePath.'<br/>';
		
		$bestImagePath=$path.str_replace('/',DIRECTORY_SEPARATOR,$best_image_link);
		if(TEMPLATESHOPScreenshots::processImage($bestImagePath,$ProcessImagePath,$min_width,$min_height))
		{
			//echo 'set completed. image processed<br/>';
			return $ProcessImageSrc;
		}
		else
		{
			//cannot find good enough image
			return '';
		}
		
	}
	
	public static function makeProcessImageSrc($templateid,$imagefolder,$min_width,$min_height)
	{
			$path=JPATH_SITE.DIRECTORY_SEPARATOR;
		
			$new_image_file='tms_'.$templateid.'_'.$min_width.'x'.$min_height.'.jpg';
			
			$folder='images/'.$imagefolder.'/_resized';
			
			$folderpath=$path.str_replace('/',DIRECTORY_SEPARATOR,$folder);
			if (!is_dir($folderpath))
					mkdir($folderpath);
			
			return $folder.'/'.$new_image_file;
	}
	
	public static function processImage($bestImagePath,$ProcessImagePath,$dst_width,$min_height)
	{
		$backgroundcolor=-1; //take color from left top pixel
		$watermark='';
		if(TEMPLATESHOPScreenshots::ProportionalResize($bestImagePath, $ProcessImagePath, $dst_width, $min_height,0.5, true,$backgroundcolor, $watermark)==1)
			return true;
		else
			return false;
	}
		
	public static function checkCachedScreenShotFile($imagefolder,$link,$min_width,$min_height,$proportions_index)
	{
		$path=JPATH_SITE.DIRECTORY_SEPARATOR;
		//echo '$link='.$link.'<br/>';
		
		//$link=str_replace('tm1://','http://scr.templatemonster.com/',$link);
		
		$src=str_replace('http://scr.template-help.com/','',$link);
		$src=str_replace('http://scr.templatemonster.com/','',$src);
		
		$filescr='images/'.$imagefolder.'/'.$src;
		$filepath=$path.str_replace('/',DIRECTORY_SEPARATOR,$filescr);
		//echo '$src='.$src.'<br/>';

		if(file_exists($filepath))
		{
			
			if(TEMPLATESHOPScreenshots::isImageSizeOk($filepath,$min_width,$min_height,$proportions_index))
				return '/'.$filescr;
			else
				return '';
		}
		else
		{
			//Load file
			$filedata=file_get_contents($link);
			if($filedata!='')
			{
				$pair=explode(DIRECTORY_SEPARATOR,$filepath);
				$dir_a=array();
				for($i=0;$i<count($pair)-1;$i++)
					$dir_a[]=$pair[$i];
					
				$dir=implode(DIRECTORY_SEPARATOR,$dir_a);//.DIRECTORY_SEPARATOR;

				if (!is_dir($dir))
					mkdir($dir);


				file_put_contents($filepath,$filedata);

				
				if(TEMPLATESHOPScreenshots::isImageSizeOk($filepath,$min_width,$min_height,$proportions_index))
					return '/'.$filescr;
				else
					return '';
			
			}
		}
		
	}
	public static function isImageSizeOk($imagefile,$min_width,$min_height,$proportions_index)
	{
		if($min_width==0 or $min_height==0)
			return true;
		//echo '$imagefile='.$imagefile.'<br/>';
		$size = getimagesize($imagefile);
		/*
		echo 'imagefile='.$imagefile.'<br/>';
		echo '$min_width='.$min_width.'<br/>';
				echo '$min_height='.$min_height.'<br/>';
				
				echo '$size[0]='.$size[0].'<br/>';
				echo '$size[1]='.$size[1].'<br/>';
		*/
		
		if($size[0]>=$min_width and $size[1]>=$min_height)
		{
			//echo 'size is ok<br/>';
			//if($min_width!=0 and $min_height!=0)
			//{
				//compare propoertions
				//echo 'compare propoertions<br/>';
				
				
				
				
				
				$p_req=$min_width/$min_height;//required proportions
				$p_img=$size[0]/$size[1];//image proportions
			
				//$p=($p_req/$p_img);
				//if($p>1-$proportions_index and $p<1+$proportions_index )
				//{
					//echo 'proportions are good<br/>';
				if($p_req>=$p_img)
					return true;
				else
					return false;
				//}
				//else
				//{
					//echo 'proportions not good<br/>';
					//return false;
				//}
			//}
			//else
			//{
				//echo 'do not compare propoertions<br/>';
				///return true;
			//}
		}
		else
		{
			//echo 'size is not ok<br/>';
		}
		
		return false;
	}
    
    
    public static function ProportionalResize($src, $dst, $dst_width, $dst_height,$LevelMax, $overwrite,$backgroundcolor, $watermark)
	{
		//This function copied from Custom Tables extension
		$fileExtension=TEMPLATESHOPScreenshots::FileExtenssion($src);
		$fileExtension_dst=TEMPLATESHOPScreenshots::FileExtenssion($dst);
	
	
	if(!$fileExtension!='')return -1;
		
	if($LevelMax>1){$LevelMax=1;}
	
	
	//Check if destination already complited
	 //and $overwrite
	if(file_exists($dst))
	{

		return 2;
	}


	$size = getImageSize($src);
	

	$ms=$size[0]*$size [1]*4;

	$width = $size[0];
	$height = $size[1];
	
	if($dst_height==0)
		$dst_height=floor($dst_width/($width/$height));
		
	if($dst_width==0)
		$dst_width=floor($dst_height*($width/$height));
	

	
	
	$rgb =$backgroundcolor;
	if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
		$from = ImageCreateFromJpeg($src);
		if($rgb==-1)
			$rgb = imagecolorat($from, 0, 0);
	}elseif ($fileExtension == "gif"){ 
		$from1 = ImageCreateFromGIF($src);
		$from = ImageCreateTrueColor ($width,$height);
		imagecopyresampled ($from,  $from1,  0, 0,  0, 0, $width, $height, $width, $height);
		if($rgb==-1)
			$rgb = imagecolorat($from, 0, 0);
	}elseif ($fileExtension == 'png'){
		

			$from = imageCreateFromPNG($src);
			if($rgb==-1)
			{
				$rgb = imagecolorat($from, 0, 0);
				
				//if destination is jpeg and background is transparent then replace it with white.
				if($rgb==2147483647 and $fileExtension_dst=='jpg')
					$rgb=16777215;
			}
			

	}//if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
	
	


	
	
	$new = ImageCreateTrueColor ($dst_width,$dst_height);
	
	if($rgb!=-2)
	{
		//Transparent
		imagefilledrectangle ($new, 0, 0, $dst_width, $dst_height,$rgb);
	}	
	else
	{

		imageSaveAlpha($new, true);
		ImageAlphaBlending($new, false);
		
		$transparentColor = imagecolorallocatealpha($new, 255, 0, 0, 127);
		imagefilledrectangle ($new, 0, 0, $dst_width, $dst_height,$transparentColor);
	}
	
	
	
	/*
	//Width
	$dst_w=$dst_width; //Dist Width
	$dst_h=round($height*($dst_w/$width));
		
	if($dst_h>$dst_height)
	{
		$dst_h=$dst_height;
		$dst_w=round($width*($dst_h/$height));
		
		//Do crop if pr
		$a=$dst_width/$dst_w;
		$x=1+($a-1)*$LevelMax;
		
		if($LevelMax!=0)
		{	$dst_w=$dst_width/$x; //Dist Width
			$dst_h=round($height*($dst_w/$width));
		}
	}

	
	


	//Setting coordinates
	$dst_x=round($dst_width/2-$dst_w/2);
	$dst_y=round($dst_height/2-$dst_h/2);
	*/
	
	$dst_x=0;
	$dst_y=0;
	
	$dst_w=$dst_width;
	$dst_h=$dst_height;

	$height_=$height;
	if($height>$dst_height)
		$height_=$width*($dst_height/$dst_width);
	
	
	imagecopyresampled ($new,  $from,  $dst_x, $dst_y,  0, 0 , $dst_w, $dst_h,  $width, $height_);


	
	
	
	if($fileExtension_dst == "jpg" OR $fileExtension_dst == 'jpeg'){ 
		imagejpeg($new, $dst, 70); 
	}elseif ($fileExtension_dst == "gif"){ 
		imagegif($new, $dst); 
	}elseif ($fileExtension_dst == 'png'){
		imagepng($new, $dst);
	}

	


	return 1;
	

	}
	
	
	
	
	public static function FileExtenssion($src)
	{
		$fileExtension='';
		$name = explode(".", strtolower($src));
		$currentExtensions = $name[count($name)-1];
		$allowedExtensions = 'jpg jpeg gif png';
		$extensions = explode(" ", $allowedExtensions);
		for($i=0; count($extensions)>$i; $i=$i+1)
		{
			if($extensions[$i]==$currentExtensions)
			{
				$extensionOK=1; 
				$fileExtension=$extensions[$i]; 
			
				return $fileExtension;
				break; 
			}
		}
	
		return $fileExtension;
	}
    
}