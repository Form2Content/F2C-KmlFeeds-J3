<?php
class F2ckmlfeedsFieldAdminAuthor extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('aut_values', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('aut_values', 'settings'); ?></div>
		</div>
		<?php 
	}
	
	public function prepareFormFilterField($form, $item)
	{
		$options	= array();
		$db 		= JFactory::getDbo();
		$query 		= $db->getQuery(true);
		
		$query->select('id, name')->from('#__users')->order('name ASC');
		
		$db->setQuery($query);
		$rowlist = $db->loadRowList(0);
		
		foreach($rowlist as $row)
		{
			$options[$row[0]] = $row[1];
		}
		
		$regOptions = new JRegistry();				
		$regOptions->set('options', $options);
		$form->setFieldAttribute('aut_values', 'options', $regOptions->toString(), 'settings');						
	}
}