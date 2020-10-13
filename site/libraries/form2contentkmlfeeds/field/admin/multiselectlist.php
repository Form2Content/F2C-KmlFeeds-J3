<?php
class F2ckmlfeedsFieldAdminMultiSelectList extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('fieldname'); ?></div>
			<div class="controls"><?php echo $form->getInput('fieldname'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('msl_values', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('msl_values', 'settings'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('msl_query_mode', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('msl_query_mode', 'settings'); ?><br/>
				<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_MULTISELECT_QUERYMODE_AND', true); ?><br/>
				<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_MULTISELECT_QUERYMODE_OR', true); ?>
			</div>
		</div>
		<?php 
		echo $form->getInput('field_id');
	}
	
	public function prepareFormFilterField($form, $item)
	{
		$regOptions = new JRegistry();				
		$regOptions->set('options', (array)$item->fieldsettings->get('msl_options'));
		$form->setFieldAttribute('msl_values', 'options', $regOptions->toString(), 'settings');						
	}
	
	static public function fieldConnections()
	{
		return array('Multiselectlist');
	}
}