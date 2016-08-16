<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class PaymentReceivingOption extends Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
		'value' => array (
			'type' => 'text',
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
			'value' => 'Cash',
			'title' => 'Cash',
		),
		array (
			'value' => 'Cheque',
			'title' => 'Cheque',
		),
		array (
			'value' => 'Bank Transfer',
			'title' => 'Bank Transfer',
		),
	);
}
?>
