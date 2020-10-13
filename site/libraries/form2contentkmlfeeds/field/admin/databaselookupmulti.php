<?php
class F2ckmlfeedsFieldAdminDatabaselookupmulti extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('fieldname'); ?></div>
			<div class="controls"><?php echo $form->getInput('fieldname'); ?></div>
		</div>
		<div class="control-group">	
			<div class="control-label"><?php echo $form->getLabel('dlm_values', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('dlm_values', 'settings'); ?></div>
		</div>
		<div class="control-group">	
		<div class="control-label"><?php echo $form->getLabel('dlm_query_mode', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('dlm_query_mode', 'settings'); ?><br/>
				<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_MULTISELECT_QUERYMODE_AND', true); ?><br/>
				<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_MULTISELECT_QUERYMODE_OR', true); ?>
			</div>
		</div>
		<?php 
		echo $form->getInput('field_id');
	}
	
	public function prepareFormFilterField($form, $item)
	{	
		// execute the query to retrieve the key and value column
		$db = JFactory::getDbo();
		$db->setQuery($item->fieldsettings->get('dlm_query'));
		$rowlist = $db->loadRowList(0);
		
		$options = array();
		
		foreach($rowlist as $row)
		{
			$options[$row[0]] = $row[1];
		}
		
		$regOptions = new JRegistry();				
		$regOptions->set('options', $options);
		$form->setFieldAttribute('dlm_values', 'options', $regOptions->toString(), 'settings');						
	}
	
	static public function fieldConnections()
	{
		return array('Databaselookupmulti');
	}
	
}