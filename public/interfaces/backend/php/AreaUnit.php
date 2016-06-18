<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class AreaUnit extends Base {
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
					'value' => 'Square Inch',
					'title' => 'Square Inch' 
			),
			array (
					'value' => 'Square Foot',
					'title' => 'Square Foot' 
			),
			array (
					'value' => 'Square Yard',
					'title' => 'Square Yard'
			),
			array (
					'value' => 'Square Mile',
					'title' => 'Square Mile'
			),
			array (
					'value' => 'Square Mili Meter',
					'title' => 'Square Mili Meter' 
			),
			array (
					'value' => 'Square Centi Meter',
					'title' => 'Square Centi Meter' 
			),
			array (
					'value' => 'Square Meter',
					'title' => 'Square Meter'
			),
			array (
					'value' => 'Square Kilo Meter',
					'title' => 'Square Kilo Meter'
			),			
		);
}
?>
