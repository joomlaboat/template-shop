	<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat 
/-------------------------------------------------------------------------------------------------------/

	@version		1.1.5
	@build			16th June, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		edit.php
	@author			Ivan Komlev <http://joomlaboat.com>	
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_templateshop');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'layouteditor.php');
require_once('_tagsets.php');

$onPageLoads=array();
?>
<script>
	// waiting spinner
	var outerDiv = jQuery('body');
	jQuery('<div id="loading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('components/com_templateshop/assets/images/import.gif') 50% 15% no-repeat")
		.css("top", outerDiv.position().top - jQuery(window).scrollTop())
		.css("left", outerDiv.position().left - jQuery(window).scrollLeft())
		.css("width", outerDiv.width())
		.css("height", outerDiv.height())
		.css("position", "fixed")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.css("display", "none")
		.appendTo(outerDiv);
	jQuery('#loading').show();
	// when page is ready remove and show
	jQuery(window).load(function() {
		jQuery('#templateshop_loader').fadeIn('fast');
		jQuery('#loading').hide();
	});
</script>

<div id="templateshop_loader" style="display: none;">
<form action="<?php echo JRoute::_('index.php?option=com_templateshop&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

<div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'presetsTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'presetsTab', 'details', JText::_('COM_TEMPLATESHOP_PRESETS_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('presets.details_left', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'presetsTab', 'layout', JText::_('COM_TEMPLATESHOP_PRESETS_LAYOUT', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php
				$textareacode=JLayoutHelper::render('presets.layout_fullwidth', $this);
				$textareaid='jform_layout';
				$textareatabid="layout";
				
				$tags=get_PageLayout_Tagsets();
				
				echo renderEditor($textareacode,$textareaid,$textareatabid,$tags,$onPageLoads);
				?>
				
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'presetsTab', 'item_layout', JText::_('COM_TEMPLATESHOP_PRESETS_ITEM_LAYOUT', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12" style="display:inline-block;">
				<?php
				$textareacode=JLayoutHelper::render('presets.item_layout_fullwidth', $this);
				$textareaid="jform_itemlayout";
				$textareatabid="item_layout";
				
				$tags=get_ItemLayout_Tagsets();
				
				echo renderEditor($textareacode,$textareaid,$textareatabid,$tags,$onPageLoads);
				?>
				
				
			</div>
			
			
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php /* if ($this->canDo->get('core.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'presetsTab', 'publishing', JText::_('COM_TEMPLATESHOP_PRESETS_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('presets.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('presets.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; */ ?>

	<?php /* if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'presetsTab', 'permissions', JText::_('COM_TEMPLATESHOP_PRESETS_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; */ ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="presets.edit" />
		<?php echo JHtml::_('form.token'); ?>
	
	</div>
</div>



<?php
/*
<script>
	colorcoding("jform_layout");
	colorcoding("jform_itemlayout");
</script>

*/
render_onPageLoads($onPageLoads);
?>
</form>
</div>
