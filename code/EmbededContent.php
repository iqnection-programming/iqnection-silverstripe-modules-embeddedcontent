<?php

class EmbededContent extends DataObject
{
	private static $db = array(
		'Title' => 'Varchar(255)',
		'EmbedCode' => 'Text',
	);

	private static $summary_fields = array(
		'Title' => 'Title'
	);

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		$fields->dataFieldByName('Title')->setDescription('Internal Use Only');
		if (class_exists('CodeeditorField'))
		{
			$fields->replaceField('EmbedCode', CodeeditorField::create('EmbedCode')
				->addExtraClass('stacked')
				->setMode('html')
				->setRows(10)
			);
		}
		$shortCodeField = ($this->ID) ? 
			TextField::create('ShortCode','Short Code')
				->setValue($this->GenerateShortCode())
				->setAttribute('readonly','readonly')
				->setDescription('Copy this short code and paste in your content where you want the embed code to appear') :
			ReadonlyField::create('ShortCode','Short Code')->setValue('Press Save to generate short code');
		$fields->insertBefore($shortCodeField , 'Title');
		return $fields;
	}

	public function canCreate($member = null) { return true; }
	public function canDelete($member = null) { return true; }
	public function canEdit($member = null)   { return true; }
	public function canView($member = null)   { return true; }

	public function GenerateShortCode()
	{
		return ($this->ID) ? '[embed_content, id="'.$this->ID.'"]' : null;
	}
	
	public function forTemplate()
	{
		return $this->renderWith(array('EmbededContent'));
	}
	
	public static function ParseShortCode($args, $content=null, $parser=null, $tagname=null)
	{
		return ($embed = EmbededContent::get()->byID($args['id'])) ? $embed->forTemplate() : null;
	}
}