<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class BankAccountType extends Base {
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
					'value' => 'Petty Cash',
					'title' => 'Petty Cash' 
			),
			array (
					'value' => 'Saving',
					'title' => 'Saving' 
			),
			array (
					'value' => 'Current',
					'title' => 'Current' 
			),
			array (
					'value' => 'Fixed Deposit',
					'title' => 'Fixed Deposit' 
			),
	);
}
?>
