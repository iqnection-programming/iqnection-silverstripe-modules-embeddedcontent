<?php


use SilverStripe\Admin\ModelAdmin;

class EmbeddedContentModelAdmin extends ModelAdmin 
{
	private static $managed_models = [
		'EmbeddedContent' => array( 'title'=>'Embedded Content' )
	];

	private static $menu_icon = 'iq-embeddedcontent/images/admin-icon.png';
	private static $menu_title = 'Embed Content';
	private static $url_segment = 'embedcontent';
	public $showImportForm = false;
}
