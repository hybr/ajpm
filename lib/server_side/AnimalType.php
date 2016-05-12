<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class AnimalType extends Base {
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
					'value' => 'Cow',
					'title' => 'Cow' 
			),
			array (
					'value' => 'Bull (Cart)',
					'title' => 'Bull (Cart)'
			),
			array (
					'value' => 'Ox (Breed)',
					'title' => 'Ox (Breed)'
			),			
			array (
					'value' => 'Dog',
					'title' => 'Dog' 
			),
			array (
					'value' => 'Bitch',
					'title' => 'Bitch'
			),
			array (
					'value' => 'Hen',
					'title' => 'Hen'
			),						

			array (
					'value' => 'Rooster',
					'title' => 'Rooster' 
			)
	);
}
?>
