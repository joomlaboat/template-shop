<?php
/**
 * TEMPLATESHOP Joomla! 3.0 Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class TMSPagination
{
	public static function getPagination($limit,$numerofpagesperbar,$total,$presetid)
	{
		$document = JFactory::getDocument();
		$document->addCustomTag('<script type="text/javascript" src="/media/com_templateshop/js/ajax.js"></script>');
		$document->addCustomTag('<script type="text/javascript" src="/media/com_templateshop/js/pagination.js"></script>');
		
		
		$jinput = JFactory::getApplication()->input;
		$currentpage=$jinput->getInt('tmspage',1);
		
		
		
		$pages=ceil($total/$limit);
		$page_offset=0;
		
		
		if($numerofpagesperbar>$pages)
			$numerofpagesperbar=$pages;
			
		
		if($pages>$numerofpagesperbar)
		{
			$page_offset=$currentpage-ceil($numerofpagesperbar/2);
			if($page_offset<0)
				$page_offset=0;
		}
			
		if($numerofpagesperbar+$page_offset>$pages)
			$numerofpagesperbar=$pages-$page_offset;
			
		$link=TMSMisc::curPageURL();
		$link=TMSMisc::deleteURLQueryOption($link, 'tmspage');
		if(strpos($link,'?')===false)
			$link.="?";
		else
			$link.="&";
		
		$result='
		<div id="tms-current-url" style="display:none;">'.$link.'</div>
		
		<div class="tms-pagination-box">
			<div class="tms-pagination">';
			
		if($currentpage>1 and $pages>$numerofpagesperbar)
			$result.='<a href="javascript:tmsPaginationSet('.($currentpage-1).','.$presetid.')">&laquo;</a>';
		
		$c=($numerofpagesperbar+$page_offset)-(1+$page_offset);
		if($c>0)//if more than one page
		{
			for($i=1+$page_offset;$i<=$numerofpagesperbar+$page_offset;$i++)
			{
				if($i==$currentpage)
					$result.='<a href="#" class="active"><b>'.$i.'</b></a>';
				else
					$result.='<a href="javascript:tmsPaginationSet('.$i.','.$presetid.')">'.$i.'</a>';
			}
		}
  
		if($currentpage<$pages and $pages>$numerofpagesperbar)
			$result.='<a href="javascript:tmsPaginationSet('.($currentpage+1).','.$presetid.')">&raquo;</a>';
			
		$result.='
			</div>
		</div>
		<div id="tms-pagination_modal_box" class="tms-pagination_modal"><div class="tms_loader"></div></div>
		';
		
		return $result;
	}
}
