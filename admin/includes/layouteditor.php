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

$theme='eclipse';
$document = JFactory::getDocument();
$document->addCustomTag('<script src="/administrator/components/com_templateshop/js/layouteditor.js"></script>');
$document->addCustomTag('<link href="/administrator/components/com_templateshop/css/layouteditor.css" rel="stylesheet">');

$document->addCustomTag('<link rel="stylesheet" href="/administrator/components/com_templateshop/includes/codemirror/lib/codemirror.css">');
//$document->addCustomTag('<link rel="stylesheet" href="/administrator/components/com_templateshop/css/codemirror.css">');
$document->addCustomTag('<link rel="stylesheet" href="/administrator/components/com_templateshop/includes/codemirror/addon/hint/show-hint.css">');

$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/lib/codemirror.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/addon/mode/overlay.js"></script>');
//$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/addon/mode/simple.js"></script>');


$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/addon/hint/show-hint.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/addon/hint/xml-hint.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/addon/hint/html-hint.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/mode/xml/xml.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/mode/javascript/javascript.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/mode/css/css.js"></script>');
$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/mode/htmlmixed/htmlmixed.js"></script>');
//$document->addCustomTag('<script src="/administrator/components/com_templateshop/includes/codemirror/mode/layouteditor/layouteditor.js"></script>');

$document->addCustomTag('<link rel="stylesheet" href="/administrator/components/com_templateshop/includes/codemirror/theme/'.$theme.'.css">');

  
  

		/*
		function renderTags($tags,$objid)
		{
			$tags_=array();
			
			foreach($tags as $tag)
			{
				if(is_array($tag))
					$tags_[]='<div><code><a href=\'javascript:addTag("['.$tag[0].']");\'>['.$tag[0].']</a></code> '.$tag[1].'</div>';
				else
					$tags_[]='<div><code><a href=\'javascript:addTag("['.$tag.']");\'>['.$tag.']</a></code></div>';
			}
			
			
			return implode('' ,$tags_);
		}
		*/
		
		function renderEditor($textareacode,$textareaid,$textareatabcode,$tags,&$onPageLoads)
		{
			$index=count($onPageLoads);
			
			
			$result='
				<table class="customlayoutform">
					<tbody>
						<tr>
							<td style="width:70%;position:relative;">
								<div class="layouteditorbox">'.$textareacode.'</div>
							</td>
							<td>
								<div id="layouteditor_tagsets'.$index.'">
								
								
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				';
			
			
			/*
			 *
			 *<div class="dynamic_values"><h4>Dynamic Values</h4>
								<div class="dynamic_values_list">'.renderTags($tags,$textareaid).'</div>
								</div>
			 *
			$result='

								<div class="layouteditorbox">'.$textareacode.'</div>

								<div class="dynamic_values">
								<h4>Dynamic Values</h4>
									<div class="dynamic_values_list">'.renderTags($tags,$textareaid).'</div>
								</div>
				';*/
			$encodedTags=json_encode($tags);
	
	//tag_sets.push(json_decode(\''.$encodedTags.'\'));
			$onPageLoads[]='
			
			tag_sets.push(JSON.parse(\''.$encodedTags.'\'));
	
			text_areas.push(["'.$textareatabcode.'",'.$index.']);
        codemirror_editors['.$index.'] = CodeMirror.fromTextArea(document.getElementById("'.$textareaid.'"), {
          mode: "layouteditor",
	   lineNumbers: true,
        lineWrapping: true,
		theme: "eclipse",
          extraKeys: {"Ctrl-Space": "autocomplete"}
          
        });
	      var charWidth'.$index.' = codemirror_editors['.$index.'].defaultCharWidth(), basePadding = 4;
      codemirror_editors['.$index.'].on("renderLine", function(cm, line, elt) {
        var off = CodeMirror.countColumn(line.text, null, cm.getOption("tabSize")) * charWidth'.$index.';
        elt.style.textIndent = "-" + off + "px";
        elt.style.paddingLeft = (basePadding + off) + "px";
      });
      
	';
			
			return $result;
		}
//text/html
	function render_onPageLoads($onPageLoads)
	{
		
		echo '
		<div id="layouteditor_Modal" class="layouteditor_modal">

  <!-- Modal content -->
  <div class="layouteditor_modal-content" id="layouteditor_modalbox">
    <span class="layouteditor_close">&times;</span>
	<div id="layouteditor_modal_content_box">
    <p>Some text in the Modal..</p>
	</div>
  </div>

</div>
		
		';
		
		
	
		
		$result='
	<script type="text/javascript">
	
	define_cmLayoutEditor();
	
	var text_areas=[];
      window.onload = function() {
        
	'.implode('',$onPageLoads).'
	
	
setTimeout(addTabExtraEvents, 10);

      };
      



    </script>';
    
	    $document = JFactory::getDocument();
		$document->addCustomTag($result);
	
	}
    
?>
