<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_RealEstateAsset extends Base {
		
	function __construct() {
		$this->collectionName = 'real_estate_asset';
	} /* __construct */
	public $fields = array (
		'live' => array (
				'type' => 'list',
				'list_class' => 'ItemLiveType',
				'input_mode' => 'clicking',
				'show_in_list' => 1,
				'default' => 'Proposed',
		),
		'type' => array (
			'help' => 'Select the type of real estate',
			'type' => 'Realestateassettype',
			'list_class' => 'RealEstateAssetType',
			'input_mode' => 'selecting',
			'default' => 'Residential Multiple Family Highrise Flat',
			'show_in_list' => 1,
			'searchable' => 1,
		),
		'owner' => array(
				'type' => 'container',
				'fields' => array (
					'name' => array (
							'type' => 'foreign_key',
							'foreign_collection' => 'person',
							'foreign_search_fields' => 'name.first,name.middle,name.last',
							'foreign_title_fields' => 'name,gender',
							'required' => 1,
							'searchable' => 1,
					),	
					'signatory' => array (
							'help' => 'Select true if signature required',
							'type' => 'list',
							'list_class' => 'Boolean',
							'input_mode' => 'clicking',
							'default' => 'True',
							'required' => 1,
					),
				),
		),			
		
		'area' => array (
				'type' => 'string',
				'show_in_list' => 1,
				'searchable' => 1,
		),
		'area_unit' => array (
				'help' => 'Select the unit of real estate area',
				'type' => 'list',
				'list_class' => 'AreaUnit',
				'input_mode' => 'selecting',
				'default' => 'Square Foot',
				'show_in_list' => 1,
		),
		'contact' => array(
				'type' => 'container',
				'required' => 1,
				'fields' => array (
						'contact' => array (
								'type' => 'foreign_key',
								'foreign_collection' => 'contact',
								'foreign_search_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building',
								'foreign_title_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building',
								'searchable' => 1,
						)
				),
		),			
		'room' => array(
				'type' => 'container',
				'required' => 1,
				'fields' => array (
						'type' => array (
								'help' => 'Select the room type of real estate',
								'type' => 'list',
								'list_class' => 'RealEstateRoomType',
								'input_mode' => 'selecting',
								'default' => 'Kitchen',
								'required' => 1,
								'searchable' => 1
						),
						'count' => array (
								'type' => 'number' ,
								'required' => 1,
						),
						'area' => array (
								'type' => 'string',
						),
						'area_unit' => array (
								'help' => 'Select the unit of real estate area',
								'type' => 'list',
								'list_class' => 'AreaUnit',
								'input_mode' => 'selecting',
								'default' => 'Square Foot',
						),
						'accessory' => array(
								'type' => 'container',
								'fields' => array (
										'name' => array (
												'help' => 'Select the accessory installed',
												'type' => 'foreign_key',
												'foreign_collection' => 'real_estate_accessory',
												'foreign_search_fields' => 'title,detail',
												'foreign_title_fields' => 'title',
												'required' => 1,
												'searchable' => 1,
										),
										'count' => array (
												'type' => 'number' ,
												'required' => 1,
										),
				
								),
						),					
				),
		),
		'feature' => array(
				'type' => 'container',
				'fields' => array (
						'name' => array (
								'help' => 'Select the accessory installed',
								'type' => 'foreign_key',
								'foreign_collection' => 'real_estate_accessory',
								'foreign_search_fields' => 'title,detail',
								'foreign_title_fields' => 'title',
								'required' => 1,
								'searchable' => 1,
						),
						'count' => array (
								'type' => 'number' ,
								'required' => 1,
						),
		
				),
		),
		'photo' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (),
				'file_name' => array (
					'type' => 'file_list',
					'required' => 1,
					'searchable' => 1
				),
				'click_link_url' => array (
					'type' => 'url',
					'searchable' => 1
				)
			)
		) ,
		'site_plan' => array (
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
		'price' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'for' => array (
					'type' => 'list',
					'help' => 'Purpose of this asset',
					'list_class' => 'ItemFor',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'default' => 'Make and Sale',
					'required' => 1,
					'searchable' => 1
				),
				'type' => array (
					'type' => 'list',
					'list_class' => 'ItemPriceType',
					'input_mode' => 'clicking',
					'default' => 'Amount',
					'required' => 1
				),
				'amount' => array (
					'type' => 'number',
					'required' => 1
				),
				'currency' => array (
					'type' => 'currency',
					'required' => 1,
					'default' => 'INR'
				),
				'per' => array (
					'type' => 'number',
					'required' => 1,
					'default' => 1
				),
				'per_unit' => array (
					'type' => 'string',
					'required' => 1
				),
			)
		),
		'visit_hours' => array (
			'help' => 'Time when seller wants to show the site.',
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'every' => array (
						'type' => 'number' ,
						'required' => 1,
				),
				'frequency' => array (
						'type' => 'list',
						'list_class' => 'TimeRepeatFrequency',
						'input_mode' => 'selecting',
						'default' => 'Day',
						'required' => 1,
				),
				'start_date' => array (
					'type' => 'date' ,
					'required' => 1,
				),
				'start_time' => array (
					'type' => 'time' ,
					'required' => 1,
				),
				'duration' => array (
					'type' => 'number' ,
					'required' => 1,
				),
				'duration_unit' => array (
					'type' => 'list',
					'list_class' => 'TimeRepeatFrequency',
					'input_mode' => 'selecting',
					'default' => 'Hour',
					'required' => 1,
				),
				'end_date' => array (
					'type' => 'date' ,
					'required' => 1,
				),
				'end_time' => array (
					'type' => 'time' ,
					'required' => 1,
				),
			)
		),
		'pre_requisites' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'mendatory' => array (
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'False',
					'required' => 1,
				),
				'condition' => array (
					'required' => 1,
				),
			)
		),				
					
	); /* fields */	

} /* class */
?>
