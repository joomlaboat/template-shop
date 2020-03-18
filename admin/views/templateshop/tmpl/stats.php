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

$document = JFactory::getDocument();
$document->addCustomTag('<link href="/administrator/components/com_templateshop/css/w3.css" rel="stylesheet">');

$document->addCustomTag('<script type="text/javascript" src="/administrator/components/com_templateshop/js/ajax.js"></script>');
$document->addCustomTag('<script type="text/javascript" src="/administrator/components/com_templateshop/js/settings.js"></script>');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'categories.php');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'templates.php');

$categories=TMSCategories::loadCategories();
$templates_count=TMSTemplates::getTemplatesCounts();

$credentialsok=(int)TMSMisc::getSettingValue('credentialsok');


?>


				
				<div>
					Categories Loaded: <b><?php echo count($categories); ?></b>
                    <div id="refreshcategoriesBox" style="display:inline-block;">
                        <button id="btn_refreshcategories" onclick='return updateCategories();' class="btn btn-small button-apply btn-success">Update</button>
                    </div>
				</div>
				
				
				
				
				<hr/>
				
				<div id="templateUpdateBox">
					<div>Templates Loaded: <?php echo '<div id="imageLoadProgress_completed" style="display:inline-block;font-weight:bold;">'.$templates_count[0]
					.'</div> of <div id="imageLoadProgress_total" style="display:inline-block;font-weight:bold;">'.$templates_count[1].'</div>'; ?></div>
					
					<div class="w3-light-grey w3-large">
					<?php
					
						if($templates_count[0]!=0)
							$p=floor(100/((int)$templates_count[1]/(int)$templates_count[0]));
						else
							$p=0;
						
						echo '<div id="imageLoadProgress" class="w3-container w3-blue" style="width:'.$p.'%">'.$p.'%</div>';
					?>
					</div>
				</div>
                    
<script>
	
	<?php
	
		if($credentialsok==1 and (int)$templates_count[0]<(int)$templates_count[1])
		{
			echo '
			
	setTimeout(function(){updateScreenshots();}, 500);
';
		}
	?>
    
</script>    