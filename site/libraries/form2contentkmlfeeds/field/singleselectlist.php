<?php
class F2ckmlfeedsFieldSingleselectlist extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$alias 	= 'fc'.$this->id;
		$values = $this->settings->get('ssl_values');
		
		if(count($values))
		{
			$inClause 	= '\'' . implode('\',\'', $values) . '\'';
			$query->join('INNER', '#__f2c_fieldcontent '.$alias.' ON '.$alias.'.formid = f.id AND '.$alias.'.fieldid='.$this->fieldId.' AND '.$alias.'.content IN ('. $inClause . ')');
		}
	}
}