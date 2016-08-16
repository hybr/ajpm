<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class WebPageType extends Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			) 
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'Other',
					'title' => 'Other' 
			),
			array (
					'value' => 'Home Page',
					'title' => 'Home Page' 
			),
			array (
					'value' => 'About Us',
					'title' => 'About Us' 
			),
	);
}
?>
