<?php
class F2ckmlfeedsFieldDatabaselookupmulti extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$values = $this->settings->get('dlm_values');
		$filterMode = $this->settings->get('dlm_query_mode', 'AND');
		
		if(count($values))
		{
			if($filterMode == 'AND')
			{							
				// AND construction: all of the values should match
				$tableAliasCounter = 0;
				
				foreach($values as $value)
				{
					// Make sure every join statement has an unique table alias
					$tableAlias = 'fc' . $this->id . $tableAliasCounter++;								
					$query->join('INNER', '#__f2c_fieldcontent '.$tableAlias.' ON '.$tableAlias.'.formid = f.id AND '.$tableAlias.'.fieldid='.$this->fieldId.' AND '.$tableAlias.'.content=\''.$value.'\'');
				}
			}
			else
			{
				// OR construction: any of the values should match
				$values = '\''.implode('\',\'', $values).'\'';
				$tableAlias = 'fc' . $this->id;								
				$query->join('INNER', '#__f2c_fieldcontent '.$tableAlias.' ON '.$tableAlias.'.formid = f.id AND '.$tableAlias.'.fieldid='.$this->fieldId.' AND '.$tableAlias.'.content IN ('.$values.')');
			}
		}
	}
}