<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class FieldType extends Base {
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
			'value' => 'String',
			'title' => 'String',
		),
		array (
			'value' => 'Number',
			'title' => 'Number',
		),
		array (
			'value' => 'Email',
			'title' => 'Email',
		),
		array (
			'value' => 'Url',
			'title' => 'Url',
		),
		array (
			'value' => 'List',
			'title' => 'List',
		),
	);
}
?>
