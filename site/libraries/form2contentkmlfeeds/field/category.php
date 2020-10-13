<?php
class F2ckmlfeedsFieldCategory extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$categoryIds = $this->settings->get('cat_values');
		
		if(count($categoryIds))
		{
			$query->where('f.catid IN (' . implode(',', $categoryIds) . ')');
		}
	}
}