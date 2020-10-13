<?php
class Form2ContentKmlFeedsHelperAdmin
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public static function addSubmenu($vName)
	{
		$canDo	= self::getActions();
		
		if ($canDo->get('core.admin'))
		{
			JHtmlSidebar::addEntry(
				JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDS_MANAGER'),
				'index.php?option=com_form2contentkmlfeeds&view=kmlfeeds',
				$vName == 'kmlfeeds' || $vName == 'kmlfeedfilters'
			);
		}
		
		JHtmlSidebar::addEntry(
			JText::_('COM_FORM2CONTENTKMLFEEDS_ABOUT'),
			'index.php?option=com_form2contentkmlfeeds&view=about',
			$vName == 'about'
		);
	}
	
	public static function getActions()
	{
		$user		= JFactory::getUser();
		$result		= new JObject;
		$assetName 	= 'com_form2contentkmlfeeds';
		$actions 	= array('core.admin');

		foreach ($actions as $action) 
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}	
}
?>
