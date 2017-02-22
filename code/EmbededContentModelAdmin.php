<?php


class EmbededContentModelAdmin extends ModelAdmin 
{
	private static $managed_models = array(
		'EmbededContent' => array( 'title'=>'Embeded Content' )
	);

	private static $menu_icon = 'iq-embededcontent/images/admin-icon.png';
	private static $menu_title = 'Embed Content';
	private static $url_segment = 'embedcontent';
	public $showImportForm = false;
}
