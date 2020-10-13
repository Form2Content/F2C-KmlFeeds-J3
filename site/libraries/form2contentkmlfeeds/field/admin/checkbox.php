<?php
class F2ckmlfeedsFieldAdminCheckbox extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		// Hidden checkbox, because the checkbox is not present in the post data when empty
		// See: https://docs.joomla.org/Talk:Checkbox_form_field_type
		?>
		<input type="hidden" name="jform[settings][chk_filter]" value="0">
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('fieldname'); ?></div>
			<div class="controls"><?php echo $form->getInput('fieldname'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('chk_filter', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('chk_filter', 'settings'); ?></div>
		</div>
		<?php 
		echo $form->getInput('field_id');
	}
	
	public function prepareFormFilterField($form, $item)
	{
	}
	
	static public function fieldConnections()
	{
		return array('Checkbox');
	}
}