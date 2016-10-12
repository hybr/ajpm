<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class ItemType extends Base {
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
	public $help = '<ul><li>A service is a means of delivering value to customers by facilitating outcomes customers want to achieve without the ownership of specific costs and risks.</li><li>A product is anything that can be offered to a market that might satisfy a want or need.</li><li>Solution is combination of product and service</li></ul>';
	public $table = array (
			array (
					'value' => 'Product',
					'title' => 'Product' 
			),
			array (
					'value' => 'Service',
					'title' => 'Service' 
			),
			array (
					'value' => 'Solution',
					'title' => 'Solution' 
			),
			array (
					'value' => 'Real Estate Sale',
					'title' => 'Real Estate Sale'
			),
			array (
					'value' => 'Milk Sale',
					'title' => 'Milk Sale'
			)
	);
}
?>
