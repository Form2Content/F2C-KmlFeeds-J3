<?php defined('JPATH_PLATFORM') or die('Restricted access'); ?>
<?php 
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'viewhelper.form2contentkmlfeeds.php');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

JForm::addFieldPath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'fields');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) 
{
	if (task == 'kmlfeedfilter.cancel') 
	{
		Joomla.submitform(task, document.getElementById('item-form'));
		return true;
	}
	
	if(!document.formvalidator.isValid(document.id('item-form')))
	{
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		return false;
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
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<h2><?php echo JText::_('COM_FORM2CONTENTKLMFEEDS_FILTER_SETTINGS'); ?></h2>
			<?php echo $this->field->displayFilterField($this->form); ?>
			<?php echo DisplayCredits(); ?>	
		<!-- End Content -->
		</div>
	</div>
	<?php echo $this->form->getInput('project_id');	?>
	<?php echo $this->form->getInput('feed_id'); ?>
	<?php echo $this->form->getInput('field_type_id'); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>