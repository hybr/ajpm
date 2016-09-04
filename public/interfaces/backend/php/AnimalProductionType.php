<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class AnimalProductionType extends Base {
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
					'value' => 'Milk',
					'title' => 'Milk' 
			),
			array (
					'value' => 'Egg',
					'title' => 'Egg'
			),
			array (
					'value' => 'Wool',
					'title' => 'Wool'
			),
			array (
					'value' => 'Honey',
					'title' => 'Honey'
			),
			array (
					'value' => 'Urine',
					'title' => 'Urine' 
			),
			array (
					'value' => 'Dung',
					'title' => 'Dung'
			),
			array (
					'value' => 'Skin',
					'title' => 'Skin'
			),
			array (
					'value' => 'Meat',
					'title' => 'Meat'
			),
	);
}
?>
