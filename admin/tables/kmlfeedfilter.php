<?php
class Form2ContentKmlFeedsTableKmlFeedFilter extends JTable
{
	/**
	 * Constructor
	 *
	 * @param database Database object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__f2c_kml_feed_filter', 'id', $db);
	}

	/**
	 * Validation
	 *
	 * @return boolean True if buffer valid
	 */
	function check()
	{
		return true;
	}
	
	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}	

    public function bind($array, $ignore = '') 
    {
       if (isset($array['settings']) && is_array($array['settings'])) 
       {
                // Convert the params field to a string.
                $parameter = new JRegistry;
                $parameter->loadArray($array['settings']);
                $array['settings'] = (string)$parameter;
       }
        
       return parent::bind($array, $ignore);
    }
}
?>