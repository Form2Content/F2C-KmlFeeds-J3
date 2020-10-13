<?php
class F2ckmlfeedsFieldCheckbox extends F2ckmlfeedsFieldBase
{
	public function filter($query)
	{
		$alias = 'fc'.$this->id;
		
		switch((int)$this->settings->get('chk_filter'))
		{
			case 1:
				$query->join('INNER', '#__f2c_fieldcontent '.$alias.' ON '.$alias.'.formid = f.id AND '.$alias.'.fieldid='.$this->fieldId.' AND '.$alias.'.content=\'true\'');								
				break;
			case 0:
				$query->join('LEFT', '#__f2c_fieldcontent '.$alias.' ON '.$alias.'.formid = f.id AND '.$alias.'.fieldid='.$this->fieldId);
				$query->where($alias.'.content IS NULL');
				break;
			default:
				break;							
		}
	}
}