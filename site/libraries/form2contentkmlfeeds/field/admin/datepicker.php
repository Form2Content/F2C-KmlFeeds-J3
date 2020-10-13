<?php
class F2ckmlfeedsFieldAdminDatepicker extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('fieldname'); ?></div>
			<div class="controls"><?php echo $form->getInput('fieldname'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('dat_operator', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('dat_operator', 'settings'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('dat_date', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('dat_date', 'settings'); ?></div>
		</div>
		<?php 
		echo $form->getInput('field_id');
	}
	
	public function prepareFormFilterField($form, $item)
	{
	}
	
	static public function fieldConnections()
	{
		return array('Datepicker');
	}
}