<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class AnimalEventType extends Base {
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
					'value' => 'Purchase',
					'title' => 'Purchase' 
			),
			array (
					'value' => 'Sell',
					'title' => 'Sell'
			),
			array (
					'value' => 'Returned',
					'title' => 'Returned'
			),
			array (
					'value' => 'Released',
					'title' => 'Released'
			),
			array (
					'value' => 'Birth',
					'title' => 'Birth'
			),			
			array (
					'value' => 'Death',
					'title' => 'Death' 
			),
			array (
					'value' => 'Health',
					'title' => 'Health'
			),
			array (
					'value' => 'In Heat',
					'title' => 'In Heat'
			),
			array (
					'value' => 'Got Crossed',
					'title' => 'Got Crossed'
			),
			array (
					'value' => 'Delivered Baby',
					'title' => 'Delivered Baby'
			),
	);
}
?>
