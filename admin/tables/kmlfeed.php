<?php
class Form2ContentKmlFeedsTableKmlFeed extends JTable
{
	/**
	 * Constructor
	 *
	 * @param database Database object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__f2c_kml_feed', 'id', $db);
	}

	/**
	 * Get the parent asset id for the record
	 *
	 * @return	int
	 * @since	3.0.0
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('Asset');
        $asset->loadByName('com_form2contentkmlfeeds');
        return $asset->id;		
	}
	 
	/**
	 * Validation
	 *
	 * @return boolean True if buffer valid
	 */
	function check()
	{
		$jConfig			= JFactory::getConfig();
		$tzoffset 			= $jConfig->get('config.offset');
		$dateNow			= JFactory::getDate(null, $tzoffset); 
		
		if(trim($this->title) == '')
		{
			$this->setError(JText::_('COM_FORM2CONTENTKMLFEEDS_ERROR_KMLFEED_TITLE_EMPTY'));
			return false;
		}

		if (trim($this->alias) == '') 
		{
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-','',$this->alias)) == '') 
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
				
		if(empty($this->id))
		{
			$user				= JFactory::getUser();
			$this->created_by 	= $user->id;
			$this->created		= $dateNow->toSql();			
		}

		$this->modified = $dateNow->toSql();
		
		return true;
	}
	
	public function store($updateNulls = false)
	{
		$db		= JFactory::getDbo();

		// Maken sure that the alias is unique
		$tableFeed		= JTable::getInstance('KmlFeed','Form2ContentKmlFeedsTable');
		$uniqueAlias	= false;
		$aliasCounter	= 2;
		$aliasOriginal	= $this->alias;
		
		while(!$uniqueAlias)
		{
			if($tableFeed->load(array('alias'=>$this->alias)) && ($tableFeed->id != $this->id || $this->id==0))
			{
				$this->alias = $aliasOriginal . $aliasCounter;
				$aliasCounter++;
				$uniqueAlias = false;
			}
			else
			{
				$uniqueAlias = true;
			} 						
		}

		return parent::store($updateNulls);
	}	 	
}
?>