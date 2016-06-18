<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class RealEstateRoomType extends Base {
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
					'value' => 'Bedroom',
					'title' => 'Bedroom'
			),
			array (
					'value' => 'Hall',
					'title' => 'Hall'
			),			
			array (
					'value' => 'Kitchen',
					'title' => 'Kitchen' 
			),
			array (
					'value' => 'Master Bedroom',
					'title' => 'Master Bedroom'
			),			
			array (
					'value' => 'Bathroom',
					'title' => 'Bathroom' 
			),
			array (
					'value' => 'Toilet',
					'title' => 'Toilet'
			),
			array (
					'value' => 'Utility',
					'title' => 'Utility'
			),						

			array (
					'value' => 'Front Yard',
					'title' => 'Front Yard' 
			),
			array (
					'value' => 'Back Yard',
					'title' => 'Back Yard' 
			),
			array (
					'value' => 'Balconey',
					'title' => 'Balconey'
			),
			array (
					'value' => 'Shade',
					'title' => 'Shade'
			),			

	);
}
?>
