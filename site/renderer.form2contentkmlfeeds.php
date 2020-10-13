<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

class Form2ContentKmlFeedsRenderer
{
	private $feed;
	private $model;
	
	function __construct($feed, $model)
	{
		$this->feed = $feed;
		$this->model = $model;
	}
	
	public function writeKmlFile()
	{	
		// Get the list of the formIds we need to render plus the belonging data
		$formIds 		= $this->model->getFormIdList($this->feed);	
		$kmlFileName 	= JPATH_SITE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_form2contentkmlfeeds'.DIRECTORY_SEPARATOR.$this->feed->alias.'.kml';
		$kml			= new SimpleXMLExtended("<?xml version=\"1.0\" encoding=\"utf-8\" ?><kml></kml>");		
		
		$kml->addAttribute('xmlns', 'http://www.opengis.net/kml/2.2');

		$doc = $kml->addChild('Document');
		$doc->addChild('name');
		$doc->name = $this->feed->title;
		
		$elementStyle = $doc->addChild('Style');
		$elementStyle->addAttribute('id', 'pin');
		$elementIconStyle = $elementStyle->addChild('IconStyle');
		$elementIcon = $elementIconStyle->addChild('Icon');
		$elementHref = $elementIcon->addChild('href');
		
		if(!empty($this->feed->icon_url))
		{
			$prefix = (stristr($this->feed->icon_url, 'http://') === FALSE) ? JURI::base() : ''; 
			$elementIcon->href = $prefix . $this->feed->icon_url;
		}
		
		if((int)$this->feed->plm_method == 1)
		{
			$itemNode = $doc->addChild('TEMPLATE_REPLACE');
		}
		else
		{ 
			if(count($formIds))
			{
				// Render the placemarkers without a full template
				$this->renderDefaultPlacemarkers($formIds, $kml);
			}
		}
		
		$fileContents = $kml->asXML();
		
		if($this->feed->extra_hdr_data)
		{
			$fileContents = preg_replace("/<\/name>/", '</name>'.$this->feed->extra_hdr_data, $fileContents, 1);
		}
		
		if($this->feed->extra_plm_data)
		{
			$fileContents = preg_replace("/<\/Document>/", $this->feed->extra_plm_data.'</Document>' , $fileContents);
		}
		
		if((int)$this->feed->plm_method == 1)
		{
			// Insert the parsed placemark templates
			$fileContents = preg_replace('/<TEMPLATE_REPLACE\s?\/>/', $this->renderPlacemarkerTemplates($formIds), $fileContents);
		}

		// Write the KML file to disk
		JFile::write($kmlFileName, $fileContents);
		
		// Update the date for the cache
		$this->model->updateRenderDate($this->feed->id);
	}		
	
	public function getFeedFromCache()
	{
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/xml; charset=UTF-8');
		
		ob_end_clean();
		
		return JFile::read(JPATH_SITE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_form2contentkmlfeeds'.DIRECTORY_SEPARATOR.$this->feed->alias.'.kml');
	}	
	
	private function renderDefaultPlacemarkers($formIds, $kml)
	{
		$formsData				= null;
		$docNodes				= $kml->xpath('Document');
		$doc					=& $docNodes[0];
		$parser					= null;
		$fieldsUsedInTemplate	= array();
		$contentTypeFieldList 	= $this->model->getContentTypeFields($this->feed->id);

		// check if the description needs to be rendered by a template
		if($this->feed->template)
		{
  			$parser = new F2cParser();
  			
			if(!$parser->addTemplate($this->feed->template, F2C_TEMPLATE_INTRO))
			{
				throw new Exception('Could not add template: ' . $this->feed->template, 500);
			}
			
			$fieldsUsedInTemplate = $this->getUsedFieldsInTemplate($this->feed->template, $parser);
		}

		// Add the fields we need for the latitude, longitude and title
		if($this->feed->latitude_field && !array_key_exists($this->feed->latitude_field, $fieldsUsedInTemplate))
		{
			$fieldsUsedInTemplate[] = $this->feed->latitude_field;
		}

		if($this->feed->longitude_field && !array_key_exists($this->feed->longitude_field, $fieldsUsedInTemplate))
		{
			$fieldsUsedInTemplate[] = $this->feed->longitude_field;
		}

		if($this->feed->title_field > 0 && !array_key_exists($this->feed->title_field, $fieldsUsedInTemplate))
    	{
    		$fieldsUsedInTemplate[] = $this->feed->title_field;
    	}
		    	
		// add the content fields to the data that will be used in the rendering process
		$formDataList = $this->model->getFormData($fieldsUsedInTemplate, $formIds);
	
	    foreach ($formDataList as $formData)
		{
			$itemNode = $doc->addChild('Placemark');
			
	  		$itemNode->addChild('name');
	  		$itemNode->addChild('description');
	  		
	  		$elementStyleUrl 	= $itemNode->addChild('styleUrl');
	  		$elementPoint 		= $itemNode->addChild('Point');
	  		$elementCoordinates	= $elementPoint->addChild('coordinates');
	  		
	  		// create the description
	  		if($this->feed->template)
	  		{
	  			$itemNode->description->addCData($this->parseTemplate($formData, $parser));
	  		}	
	  			  		
	  		if($this->feed->title_field == -1)
	  		{
	  			$itemNode->name	= $formData->title;
	  		}
	  		else 
	  		{
	  			$fieldName 		= $contentTypeFieldList[$this->feed->title_field]->fieldname;
	  			$itemNode->name = $formData->fields[$fieldName]->values['VALUE'];
	  		}
	  		
			$field 	= $formData->fields[$contentTypeFieldList[$this->feed->latitude_field]->fieldname];
	  		$lat 	= is_a($field, 'F2cFieldGeocoder') ? $field->values['LAT'] : $field->values['VALUE'];
			$field 	= $formData->fields[$contentTypeFieldList[$this->feed->longitude_field]->fieldname];
	  		$lon 	= is_a($field, 'F2cFieldGeocoder') ? $field->values['LON'] : $field->values['VALUE'];
	  		
	   		$itemNode->styleUrl 		= '#pin';
	   		$elementPoint->coordinates 	= $lon.','.$lat;  		
		}	
	}
	
	private function renderPlacemarkerTemplates($formIds)
	{
		$renderedData 	= '';		
		$parser 		= new F2cParser();
		
		if(!$parser->addTemplate($this->feed->plm_template, F2C_TEMPLATE_INTRO))
		{
			JError::raiseError(500, 'Could not add template: ' . $this->feed->plm_template);
		}
		
		$fieldsUsedInTemplate = $this->getUsedFieldsInTemplate($this->feed->plm_template, $parser);

		// add the content fields to the data that will be used in the rendering process
		$formDataList = $this->model->getFormData($fieldsUsedInTemplate, $formIds);		

		// render the placemark for each feed item
		foreach($formDataList as $formData)
		{
			$renderedData .= $this->parseTemplate($formData, $parser);
		}

		return $renderedData;		
	}			
	
	private function getUsedFieldsInTemplate($templateName, $parser)
	{
		$usedTemplateVars 		= array();
		$fieldList				= array();
		$fieldsUsedInTemplate	= array();
		
		// Get the fields for the Content Type
		$contentTypeFieldObjectList = $this->model->getContentTypeFields($this->feed->id);
		$possibleTemplateParameters = $parser->getPossibleTemplateVars($contentTypeFieldObjectList);
		
		$parser->getTemplateVars($possibleTemplateParameters, $usedTemplateVars);

		if(count($usedTemplateVars))
		{
			foreach($usedTemplateVars as $usedTemplateVar => $usedTemplateVarUpper)
			{
				foreach($contentTypeFieldObjectList as $contentTypeField)
				{
					if($contentTypeField->fieldname == $usedTemplateVar)
					{
						$fieldsUsedInTemplate[] = $contentTypeField->id;
						break;
					}
				}
			}
		}

		return $fieldsUsedInTemplate;
	}

	private function parseTemplate($formData, $parser)
	{
		if(!$parser->addVars($formData))
		{
			JError::raiseError(500, 'Could not add variables to template parser');
		}
		
		// Add the sef url of the article as a template parameter 
		$slug 		= ($formData->alias) ? $formData->reference_id.':'.$formData->alias : $formData->reference_id;
		$catslug 	= ($formData->catAlias) ? $formData->catid.':'.$formData->catAlias : $formData->catid;			
		$sefUrl 	= JRoute::_(ContentHelperRoute::getArticleRoute($slug, $catslug));
		
		$parser->addVar('JOOMLA_ARTICLE_LINK_SEF', $sefUrl);		
		
		return $parser->parseIntro();
	}
}
?>