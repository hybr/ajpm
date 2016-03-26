<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_RealEstateAsset extends Base {
		
	function __construct() {
		$this->collectionName = 'real_estate_asset';
	} /* __construct */
	public $fields = array (
		'type' => array (
			'help' => 'Select the type of real estate',
			'type' => 'list',
			'list_class' => 'RealEstateAssetType',
			'input_mode' => 'selecting',
			'default' => 'Home',
			'show_in_list' => 1,
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
												'type' => 'list',
												'list_class' => 'RealEstateAccessories',
												'input_mode' => 'selecting',
												'default' => 'Fan',
										),
										'count' => array (
												'type' => 'number' ,
												'required' => 1,
										),
				
								),
						),					
				),
		),
		'accessory' => array(
				'type' => 'container',
				'fields' => array (
						'name' => array (
								'help' => 'Select the accessory installed',
								'type' => 'list',
								'list_class' => 'RealEstateAccessories',
								'input_mode' => 'selecting',
								'default' => 'Fan',
						),
						'count' => array (
								'type' => 'number' ,
								'required' => 1,
						),
		
				),
		),				
	); /* fields */	

} /* class */
?>
