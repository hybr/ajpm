<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class WebPageComponent extends Base {
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
					'value' => 'Learn More',
					'title' => 'Learn More' 
			),
			array (
					'value' => 'Image Slider',
					'title' => 'Image Slider' 
			),
			array (
					'value' => 'Media Boxes',
					'title' => 'Media Boxes'
			),
			array (
					'value' => 'Paragraph',
					'title' => 'Paragraph' 
			),
			array (
					'value' => 'Video URL Link',
					'title' => 'Video URL Link'
			),
			array (
					'value' => 'Image URL Link',
					'title' => 'Image URL Link'
			),
			array (
					'value' => 'Facebook Link',
					'title' => 'Facebook Link'
			),
			array (
					'value' => 'Contacts',
					'title' => 'Contacts'
			)
	);
}
?>
