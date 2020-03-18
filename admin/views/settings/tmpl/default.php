<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		default.php
	@author			Ivan Komlev <http://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');


?>
<?php /* if ($this->canDo->get('settings.access')): */?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task === 'settings.back') {
			parent.history.back();
			return false;
		} else {
			var form = document.getElementById('adminForm');
			form.task.value = task;
			form.submit();
		}
	}
</script>
<?php $urlId = (isset($this->item->id)) ? '&id='. (int) $this->item->id : ''; ?>
<form action="<?php echo JRoute::_('index.php?option=com_templateshop&view=settings'.$urlId); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
</form>
<?php
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');

?>


<div id="tmsFormContent">
				<p><br/></p>
				<h4>Template Monster Affiliate Program</h4>
				<p>In order to allow Template Shop to fetch template data you have to register your own account.</p>
				<p>Visit this page: <a href="https://secure.mytemplatestorage.com/personal" target="_blank">https://secure.mytemplatestorage.com/personal</a></p>

				<p>
				Template Monster Affiliate Program Login:<br/>
				<input name="login" style="width:400px;" value="<?php echo TMSMisc::getSettingValue('login'); ?>" />
				</p>

				<hr/>

				<p>
					Web API Password:<br/>
					<input name="webapipassword" style="width:400px;" value="<?php echo TMSMisc::getSettingValue('webapipassword'); ?>" />

					<br/>Visit this page: <a href="https://secure.mytemplatestorage.com/personal/webapi.php" target="_blank">https://secure.mytemplatestorage.com/personal/webapi.php</a>
					<br/>Place password only. Example "594973f2b200000000c5a123b4900000"
				</p>

				<hr/>

				<p>
					Template Monster Affiliate Program Preset Code:<br/>
					<input name="presetcode" style="width:400px;" value="<?php echo TMSMisc::getSettingValue('presetcode'); ?>" />

					<br/>Visit this page: <a href="https://secure.mytemplatestorage.com/personal/preset_control.php" target="_blank">https://secure.mytemplatestorage.com/personal/preset_control.php</a>
					<br/>This ID will be used to show template Live Demo (when possible).
				</p>

				<hr/>

				<p>
					<?php //echo $this->form->getLabel('imagefolder');?>
					Image Cache Folder:
					<?php
						if($this->item['imagefolder']=='')
						{
							$tmp_imagefolder='tms_cache';
							$style1='style="display:none;"';
							$style2='style="display:block;"';
						}
						else
						{
							$tmp_imagefolder='';
							$style1='style="display:block;"';
							$style2='style="display:none;"';
							;
						}

					?>
					<div id="selectFolderBox" <?php echo $style1; ?>>
						<?php echo $this->form->getInput('imagefolder'); ?>
						<button id="btn_createfolder" onclick='return createFolder(event);' class="btn btn-small button-apply btn-success">Create new Folder</button>
					</div>
					<div id="createFolderBox" <?php echo $style2; ?>>
						images/<input name="newfoldername" style="width:400px;" value="<?php echo $tmp_imagefolder; ?>" />
						<button id="btn_savenewfolder" onclick='Joomla.submitbutton("settings.createFolder");' class="btn btn-small button-apply btn-success">Create</button>
						<button id="btn_cancelfolder" onclick='return cancelFolder(event);' class="btn btn-small button-apply btn-cancel">Cancel</button>
					</div>

				</p>


<script>

	function createFolder(e)
	{
		e.preventDefault();

		var obj1=document.getElementById("selectFolderBox");
		var obj2=document.getElementById("createFolderBox");

		obj1.style="display:none;";
		obj2.style="display:block;";

		return false;
	}

	function cancelFolder(e)
	{
		e.preventDefault();

		var obj1=document.getElementById("selectFolderBox");
		var obj2=document.getElementById("createFolderBox");

		obj1.style="display:block;";
		obj2.style="display:none;";

		return false;
	}

    var obj1=document.getElementById("adminForm");
    var obj2=document.getElementById("tmsFormContent");

    obj1.innerHTML=obj1.innerHTML+obj2.innerHTML;
    obj2.innerHTML="";
</script>
