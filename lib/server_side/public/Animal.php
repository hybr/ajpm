<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Animal extends Base {
		
	function __construct() {
		$this->collectionName = 'animal';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'string',
			'show_in_list' => 1,
			'searchable' => 1,
		),
		'tag_number' => array (
			'type' => 'string',
			'show_in_list' => 1,
			'required' => 1,
			'searchable' => 1,
		),
		'mother_tag_number' => array (
			'type' => 'string',
			'show_in_list' => 1,
				
		),
		'birth_date' => array (
			'type' => 'date' ,
		),
		'purchase_date' => array (
			'type' => 'date' ,
		),
		'purchase_cost' => array (
			'type' => 'number' ,
		),
		'purchase_contacts' => array(
			'type' => 'container',
			'fields' => array (
				'contact' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'contact',
					'foreign_search_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building',
					'foreign_title_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building'
				)                                                    
			),
		),		
		'type' => array (
			'help' => 'Select the gender and type of animal',
			'type' => 'list',
			'list_class' => 'AnimalType',
			'input_mode' => 'selecting',
			'default' => 'Cow',
			'show_in_list' => 1,
			'required' => 1,
			'searchable' => 1,
		),
		'provider' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender',
			'show_in_list' => 1,
			'searchable' => 1,
		),
		'photo' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (
					'searchable' => 1,
				),
				'file_name' => array (
					'type' => 'file_list',
					'searchable' => 1,
					'required' => 1
				),
				'click_link_url' => array (
					'searchable' => 1,
					'type' => 'url'
				)
			)
		) ,			
		'sale_date' => array (
				'type' => 'date' ,
		),
		'sale_cost' => array (
				'type' => 'number' ,
		),			
	); /* fields */	

} /* class */
?>
