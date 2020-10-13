<?php
class F2ckmlfeedsFieldDatepicker extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$alias 		= 'fc'.$this->id;
		$operator 	= '';
		$date 		= new JDate($this->settings->get('dat_date'));
		
		switch($this->settings->get('dat_operator'))
		{
			case 'LT':
				$operator = '<';
				break;	
			case 'LTEQ':
				$operator = '<=';
				break;	
			case 'EQ':
				$operator = '=';
				break;	
			case 'GT':
				$operator = '>';
				break;	
			case 'GTEQ':
				$operator = '>=';
				break;	
		}
		$condition 	= $operator . ' \'' . $date->toISO8601() . '\'';

		$query->join('INNER', '#__f2c_fieldcontent '.$alias.' ON '.$alias.'.formid = f.id AND '.$alias.'.fieldid='.$this->fieldId.' AND '.$alias.'.content ' . $condition);						
	}
}