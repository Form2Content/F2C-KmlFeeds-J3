<?php defined('JPATH_PLATFORM') or die('Restricted access'); ?>
<?php
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'viewhelper.form2contentkmlfeeds.php');

JHTML::stylesheet( 'default.css', 'administrator/components/com_form2content/media/css/' );

JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
<!--
Joomla.submitbutton = function(task) 
{
	if (task == 'kmlfeedfilter.cancel' || document.adminForm.filterfieldselect.value != '') 
	{
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else 
	{
		alert('<?php echo $this->escape(JText::_('COM_FORM2CONTENTKMLFEEDS_ERROR_SELECT_FILTERFIELD', true));?>');
	}
}
-->	
</script>
<form action="<?php echo JRoute::_('index.php?option=com_form2contentkmlfeeds'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span12 form-horizontal">
			<div class="control-group">
				<div class="control-label"><?php echo JText::_('COM_FORM2CONTENTKLMFEEDS_FILTERFIELD'); ?></div>
				<div class="controls">
					<select name="filterfieldselect" id="filterfieldselect" class="inputbox">
						<option value="">- <?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_SELECT_FILTERFIELD');?> -</option>
						<?php echo JHtml::_('select.options', $this->filterFieldList, 'value', 'text', -1);?>
					</select>
				</div>
			</div>
		</div>
		<!-- End Content -->
		<?php echo DisplayCredits(); ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="feed_id" value="<?php echo $this->feedId; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>