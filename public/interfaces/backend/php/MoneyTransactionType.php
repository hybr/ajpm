<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class MoneyTransactionType extends Base {
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
					'value' => 'Money Received',
					'title' => 'Money Received',
			),
			array (
					'value' => 'Money Paid',
					'title' => 'Money Paid',
			),
			array (
					'value' => 'Money To Be Paid',
					'title' => 'Money To Be Paid',
			),
			array (
					'value' => 'Money To Be Received',
					'title' => 'Money To Be Received',
			),
	);
}
?>
