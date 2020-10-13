<?php defined('JPATH_PLATFORM') or die('Restricted access'); ?>
<?php 
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'viewhelper.form2contentkmlfeeds.php');

JForm::addFieldPath(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR. 'models'.DIRECTORY_SEPARATOR.'fields');
JForm::addFieldPath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'fields');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) 
{
	if (task == 'kmlfeed.cancel') 
	{
		Joomla.submitform(task, document.getElementById('item-form'));
		return true;
	}
	
	if(!document.formvalidator.isValid(document.id('item-form')))
	{
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		return false;
	}

	var fldRenderPlmTemplate = document.getElementById('jform_plm_method0');

	if(fldRenderPlmTemplate.checked)
	{
		var fldLatField = document.getElementById('jform_latitude_field');
		var fldLonField = document.getElementById('jform_longitude_field');

		if(!fldLatField.value)
		{
			alert('<?php echo $this->escape(JText::_('COM_FORM2CONTENTKMLFEEDS_ERROR_SELECT_LATITUDE_FIELD'));?>');
			return false;
		}

		if(!fldLatField.value)
		{
			alert('<?php echo $this->escape(JText::_('COM_FORM2CONTENTKMLFEEDS_ERROR_SELECT_LONGITUDE_FIELD'));?>');
			return false;
		}		
	}
	else
	{
		var fldPlmTemplate = document.getElementById('jform_plm_template_name');
		
		if(!fldPlmTemplate.value)
		{
			alert('<?php echo $this->escape(JText::_('COM_FORM2CONTENTKMLFEEDS_ERROR_SELECT_PLACEMARKER_TEMPLATE'));?>');
			return false;
		}		
	}
	
	Joomla.submitform(task, document.getElementById('item-form'));
	return true;
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_form2contentkmlfeeds&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
<div class="row-fluid">
	<!-- Begin Content -->
	<div class="span12 form-horizontal">
		<h2><?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_GENERAL'); ?></h2>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('cacheUrl'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('cacheUrl'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('renderUrl'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('renderUrl'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('contenttype'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('contenttype'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('creator'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('creator'); ?></div>
		</div>
		
		<h2><?php echo JText::_('COM_FORM2CONTENTKLMFEEDS_DETAILS'); ?></h2>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('plm_method'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('plm_method'); ?></div>
		</div>
		
		<ul class="nav nav-tabs">
			<li class="active"><a href="#default" data-toggle="tab"><?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_DEFAULT');?></a></li>
			<li><a href="#template" data-toggle="tab"><?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_TEMPLATE_BASED');?></a></li>
		</ul>
		
		<div class="tab-content">
			<!-- Begin Tabs -->
			<div class="tab-pane active" id="default">
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('title_field'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('title_field'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('latitude_field'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('latitude_field'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('longitude_field'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('longitude_field'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('template'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('template'); ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="template">
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('plm_template'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('plm_template'); ?></div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Tabs -->
		</div>
		
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('icon_url'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('icon_url'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('cache_duration'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('cache_duration'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('extra_hdr_data'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('extra_hdr_data'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('extra_plm_data'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('extra_plm_data'); ?></div>
		</div>
		
		<?php echo $this->form->getInput('project_id');	?>
		<?php echo DisplayCredits(); ?>	
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>
</form>