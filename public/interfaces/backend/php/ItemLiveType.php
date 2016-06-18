<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class ItemLiveType extends Base {
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
					'value' => 'Proposed',
					'title' => 'Proposed' 
			),
			array (
					'value' => 'Live',
					'title' => 'Live' 
			),
			array (
					'value' => 'Archived',
					'title' => 'Archived' 
			) 
	);
	public $help = '<ul><li>Proposed: service under development and not yet live</li><li>Live: service offered in production</li><li>Archived: service no longer offered</li></ul>';
}
?>
