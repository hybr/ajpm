<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class ItemFor extends Base {
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
			'value' => 'Purchase and Consume',
			'title' => 'Purchase and Consume'
		),			
		array (
			'value' => 'Purchase and Sale',
			'title' => 'Purchase and Sale' 
		),
		array (
			'value' => 'Make and Consume',
			'title' => 'Make and Consume'
		),
		array (
			'value' => 'Make and Sale',
			'title' => 'Make and Sale' 
		)
	);
}
?>
