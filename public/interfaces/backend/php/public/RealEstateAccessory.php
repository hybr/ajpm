<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_RealEstateAccessory extends Base {
	function __construct() {
		$this->collectionName = 'real_estate_accessory';
	}
	public $fields = array (
		'title' => array (
				'type' => 'string',
				'required' => 1
		),		
		'detail' => array (
				'type' => 'string',
				'required' => 1 
		),
		'photo' => array (
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
						'caption' => array (),
						'file_name' => array (
								'type' => 'file_list',
								'required' => 1
						),
						'click_link_url' => array (
								'type' => 'url'
						)
				)
		) ,			
	);
	
} /* class */
?>
