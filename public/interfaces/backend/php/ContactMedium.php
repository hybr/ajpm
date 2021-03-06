<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class ContactMedium extends Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			),
			'icon' => array (
					'type' => 'text',
					'required' => 1
			)
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'Phone',
					'title' => 'Phone',
					'icon' => 'ui-icon-calculator'
			),
			array (
					'value' => 'Email',
					'title' => 'Email',
					'icon' => 'ui-icon-mail-closed' 
			),
			array (
					'value' => 'Postal',
					'title' => 'Postal',
					'icon' => 'ui-icon-home' 
			),
			array (
					'value' => 'Web',
					'title' => 'Web',
					'icon' => 'ui-icon-arrow-4-diag' 
			),
			array (
					'value' => 'Fax',
					'title' => 'Fax',
					'icon' => 'ui-icon-print' 
			),
			array (
					'value' => 'VoIP',
					'title' => 'VoIP',
					'icon' => 'ui-icon-bookmark' 
			),
			array (
					'value' => 'Pager',
					'title' => 'Pager',
					'icon' => 'ui-icon-calculator'
			)
	);
}
?>
