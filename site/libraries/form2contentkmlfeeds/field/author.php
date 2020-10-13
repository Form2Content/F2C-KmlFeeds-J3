<?php
class F2ckmlfeedsFieldAuthor extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$authorIds = $this->settings->get('aut_values');
		
		if(count($authorIds))
		{
			$query->where('f.created_by IN (' . implode(',', $authorIds) . ')');
		}
	}
}