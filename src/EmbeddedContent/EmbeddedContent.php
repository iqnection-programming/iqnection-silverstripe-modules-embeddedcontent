<?php

namespace IQnection\EmbeddedContent;

use SilverStripe\ORM;
use SilverStripe\Forms;
use NathanCox\CodeEditorField\CodeeditorField;

class EmbeddedContent extends ORM\DataObject
{
	private static $table_name = 'EmbeddedContent';
	
	private static $db = [
		'Title' => 'Varchar(255)',
		'EmbedCode' => 'Text',
		'Alignment' => "Enum('Inline,Left Wrap,Right Wrap,Centered','Inline')",
		'Width' => 'Varchar(255)',
		'CssClass' => 'Varchar(255)',
	];

	private static $summary_fields = [
		'Title' => 'Title'
	];

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
		else
		{
			$fields->dataFieldByName('EmbedCode')->addExtraClass('monotype');
		}
		$shortCodeField = ($this->ID) ? 
			Forms\TextField::create('ShortCode','Short Code')
				->setValue($this->GenerateShortCode())
				->setAttribute('readonly','readonly')
				->setDescription('Copy this short code and paste in your content where you want the embed code to appear') :
			Forms\ReadonlyField::create('ShortCode','Short Code')->setValue('Press Save to generate short code');
		$fields->insertBefore($shortCodeField , 'Title');
		
		$fields->addFieldToTab('Root.Styles', Forms\TextField::create('CssClass','CSS Class') );
		$fields->addFieldToTab('Root.Styles', Forms\DropdownField::create('Alignment','Alignment')
			->setSource($this->dbObject('Alignment')->enumValues()) );
		$fields->addFieldToTab('Root.Styles', Forms\TextField::create('Width','Width')
			->setDescription('Will never exceed parent container size.<br />Set a fixed minimum width: 500px<br />Set a percentage minimum width: 75%'));
		
		$this->extend('updateCMSFields',$fields);
		return $fields;
	}

	public function canCreate($member = null,$context = array()) { return true; }
	public function canDelete($member = null,$context = array()) { return true; }
	public function canEdit($member = null,$context = array())   { return true; }
	public function canView($member = null,$context = array())   { return true; }

	public function validate()
	{
		$result = parent::validate();
		if ( ($this->Width) && (!preg_match('/^[0-9.]+(\%|px)$/',$this->Width)) )
		{
			$result->addError('Width is not a valid value');
		}
		return $result;
	}
	
	public function GenerateShortCode()
	{
		$shortCode = ($this->ID) ? '[embed_content, id="'.$this->ID.'"]' : null;
		$this->extend('updateShortCode',$shortCode);
		return $shortCode;
	}
	
	public function CssClasses()
	{
		$classes = array($this->CssClass);
		if ($this->Alignment)
		{
			$classes[] = preg_replace('/\s/','-',strtolower($this->Alignment));
		}
		$this->extend('updateCssClasses',$classes);
		return explode(' ',$classes);
	}
	
	public function CssWidth()
	{
		if (preg_match('/^[0-9.]+(\%|px)$/',$this->Width))
		{
			return $this->Width;
		}
	}
	
	public function forTemplate()
	{
		return $this->renderWith(array('IQnection/EmbeddedContent/EmbeddedContent'));
	}
	
	public static function ParseShortCode($args, $content=null, $parser=null, $tagname=null)
	{
		return ($embed = self::get()->byID($args['id'])) ? $embed->forTemplate() : null;
	}
}