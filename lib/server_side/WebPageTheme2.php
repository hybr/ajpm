<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class WebPageTheme2 extends Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'help' => 'primary, accent, warn, background, darkness',
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
			'value' => 'indigo_brown_orange_amber',
			'title' => 'indigo_brown_orange_amber',
		),
		array (
			'value' => 'brown_grey_red_lime_dark',
			'title' => 'brown_grey_red_lime_dark',
		),
		array (
			'value' => 'orange_amber_yellow_lime',
			'title' => 'orange_amber_yellow_lime',
		),
		array (
			'value' => 'green_light-green_yellow_lime',
			'title' => 'green_light-green_yellow_lime',
		),
		array (
			'value' => 'purple_deep-purple_red_pink',
			'title' => 'purple_deep-purple_red_pinke',
		),
	);
}
?>
