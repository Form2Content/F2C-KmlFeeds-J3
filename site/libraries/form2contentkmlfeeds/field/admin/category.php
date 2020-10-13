<?php
class F2ckmlfeedsFieldAdminCategory extends F2ckmlfeedsFieldAdminBase
{
	public function displayFilterField($form)
	{
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('cat_values', 'settings'); ?></div>
			<div class="controls"><?php echo $form->getInput('cat_values', 'settings'); ?></div>
		</div>
		<?php 
	}
	
	public function prepareFormFilterField($form, $item)
	{
		$options	= array();
		$list 		= $this->getCategoryList();
		
		foreach($list as $listItem)
		{
			$options[$listItem->id] = $listItem->title;
		}
		
		$regOptions = new JRegistry();				
		$regOptions->set('options', $options);
		$form->setFieldAttribute('cat_values', 'options', $regOptions->toString(), 'settings');						
	}
	
	private function getCategoryList()
	{
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('a.id, CONCAT(REPEAT(\'-\', a.level - 1), a.title) AS title');
		$query->from('#__categories AS a');
		$query->where('a.parent_id > 0');
		$query->where('extension = \'com_content\'');
		$query->where('a.published = 1');
		$query->order('a.lft');

		$db->setQuery($query);
		return $db->loadObjectList();		
	}
}