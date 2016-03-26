<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class LengthUnit extends Base {
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
					'value' => 'Inch',
					'title' => 'Inch' 
			),
			array (
					'value' => 'Foot',
					'title' => 'Foot' 
			),
			array (
					'value' => 'Yard',
					'title' => 'Yard'
			),
			array (
					'value' => 'Mile',
					'title' => 'Mile'
			),
			array (
					'value' => 'Mili Meter',
					'title' => 'Mili Meter' 
			),
			array (
					'value' => 'Centi Meter',
					'title' => 'Centi Meter' 
			),
			array (
					'value' => 'Meter',
					'title' => 'Meter'
			),
			array (
					'value' => 'Kilo Meter',
					'title' => 'Kilo Meter'
			),			
		);
}
?>
