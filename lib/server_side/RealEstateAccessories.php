<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class RealEstateAccessories extends Base {
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
					'value' => 'Air Conditioner',
					'title' => 'Air Conditioner' 
			),
			array (
					'value' => 'Air Heater',
					'title' => 'Air Heater'
			),
			array (
					'value' => 'Fan',
					'title' => 'Fan'
			),			
			array (
					'value' => 'Tubelight',
					'title' => 'Tubelight' 
			),
			array (
					'value' => 'CFL Bulb',
					'title' => 'CFL Bulb'
			),
			array (
					'value' => 'LED Bulb',
					'title' => 'LED Bulb'
			),						

			array (
					'value' => 'Cooler',
					'title' => 'Cooler' 
			),
			array (
					'value' => 'Cable Box',
					'title' => 'Cable Box' 
			),
			array (
					'value' => 'Cabinets',
					'title' => 'Cabinets'
			),
			array (
					'value' => 'Washing Machine',
					'title' => 'Washing Machine'
			),
			array (
					'value' => 'Dish Washer',
					'title' => 'Dish Washer'
			),
			array (
					'value' => 'Chimney',
					'title' => 'Chimney'
			),
			array (
					'value' => 'Boundry Wall',
					'title' => 'Boundry Wall'
			),
			array (
					'value' => 'Wire Fence',
					'title' => 'Wire Fence'
			),			
			array (
					'value' => 'Borewell',
					'title' => 'Borewell'
			),
			array (
					'value' => 'Well',
					'title' => 'Well'
			),
			array (
					'value' => 'Electric Meter',
					'title' => 'Electric Meter'
			),
			array (
					'value' => 'Water Meter',
					'title' => 'Water Meter'
			),
			array (
					'value' => 'Pipeline Cooking Gas',
					'title' => 'Pipeline Cooking Gas'
			),			

	);
}
?>
