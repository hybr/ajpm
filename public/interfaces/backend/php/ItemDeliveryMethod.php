<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class ItemDeliveryMethod extends Base {
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
	public $help = '<ul><li>Mail: send the product by post.</li><li>Onsite: customer pickup the product or service from a store.</li><li>Electronic: either by email of web account</li><li>Local: our staff person will deliver at your address.</li></ul>';
	public $table = array (
			array (
				'value' => 'Normal Mail',
				'title' => 'Normal Mail' 
			),
			array (
				'value' => 'Fast Mail',
				'title' => 'Fast Mail' 
			),
			array (
				'value' => 'Express Mail',
				'title' => 'Express Mail' 
			),
			array (
				'value' => 'Electronic Mail',
				'title' => 'Electronic Mail' 
			),
			array (
				'value' => 'Online Account',
				'title' => 'Online Account' 
			),
			array (
				'value' => 'Onsite',
				'title' => 'Onsite' 
			),
			array (
				'value' => 'At your address by our local staff',
				'title' => 'At your address by our local staff' 
			) 
	);
}
?>
