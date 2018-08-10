<?php

namespace IQnection\EmbeddedContent;

use SilverStripe\Admin\ModelAdmin;

class EmbeddedContentModelAdmin extends ModelAdmin 
{
	private static $managed_models = [
		\IQnection\EmbeddedContent\EmbeddedContent::class => [
			'title'=>'Embedded Content'
		]
	];

	private static $menu_icon = 'resources/iqnection-modules/embededcontent/images/admin-icon.png';
	private static $menu_title = 'Embed Content';
	private static $url_segment = 'embedcontent';
	public $showImportForm = false;
}
