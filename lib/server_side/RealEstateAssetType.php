<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class RealEstateAssetType extends Base {
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
					'value' => 'Home',
					'title' => 'Home' 
			),
			array (
					'value' => 'Bunglow',
					'title' => 'Bunglow'
			),
			array (
					'value' => 'Flat',
					'title' => 'Flat'
			),			
			array (
					'value' => 'Agriculture Land',
					'title' => 'Agriculture Land' 
			),
			array (
					'value' => 'Commercial Land',
					'title' => 'Commercial Land'
			),
			array (
					'value' => 'Redidential Land',
					'title' => 'Redidential Land'
			),						

			array (
					'value' => 'Shop',
					'title' => 'Shop' 
			),
			array (
					'value' => 'Building',
					'title' => 'Building' 
			),
			array (
					'value' => 'Mall',
					'title' => 'Mall'
			)

	);
	public $help = '<ul><li>Proposed: service under development and not yet live</li><li>Live: service offered in production</li><li>Archived: service no longer offered</li></ul>';
}
?>
