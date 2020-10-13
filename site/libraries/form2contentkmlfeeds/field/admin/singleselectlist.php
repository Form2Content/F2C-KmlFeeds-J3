<?php
class F2ckmlfeedsFieldAdminSingleselectlist extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('fieldname'); ?></div>
			<div class="controls"><?php echo $form->getInput('fieldname'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('ssl_values', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('ssl_values', 'settings'); ?></div>
		</div>
		<?php 
		echo $form->getInput('field_id');
	}
	
	public function prepareFormFilterField($form, $item)
	{
		$regOptions = new JRegistry();				
		$regOptions->set('options', (array)$item->fieldsettings->get('ssl_options'));
		$form->setFieldAttribute('ssl_values', 'options', $regOptions->toString(), 'settings');						
	}
	
	static public function fieldConnections()
	{
		return array('Singleselectlist');
	}
}