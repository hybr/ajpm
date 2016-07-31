<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_ItemDailyDistributionPayment extends Base {
		
	function __construct() {
		$this->collectionName = 'item_daily_distribution_payment';
	} /* __construct */
	public $fields = array (
		'item' => array (
				'type' => 'foreign_key',
				'show_in_list' => 1,
				'foreign_collection' => 'item',
				'foreign_search_fields' => 'title,summary',
				'foreign_title_fields' => 'type,title',
		),
		'paid_amount' => array (
			'type' => 'number',
			'show_in_list' => 1,
			'required' => 1,
		),
		'paid_amount_currency' => array (
				'type' => 'currency',
				'required' => 1,
				'default' => 'INR',
		),
		'paid_date' => array (
				'type' => 'date' ,
				'required' => 1,
				'show_in_list' => 1,
		),
		'start_date' => array (
				'type' => 'date' ,
				'required' => 1,
				'show_in_list' => 1,
		),
		'distribution_time' => array (
				'type' => 'time' ,
				'required' => 1,
				'show_in_list' => 1,
		),
		'paid_by' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'person',
				'foreign_search_fields' => 'name.first,name.middle,name.last',
				'foreign_title_fields' => 'name,gender',
				'show_in_list' => 1,
		),		
		'delivery_location' => array (
				'type' => 'string',
				'required' => 1,
		),
		'daily_quantity' => array (
				'type' => 'number',
				'required' => 1,
		),
		'daily_quantity_unit' => array (
				'type' => 'string',
				'required' => 1,
		),
		'instructions' => array (
				'type' => 'string',
		),
			'recreate_daily_records' => array (
					'help' => 'Recreate daily records once',
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'False',
					'required' => 1,
			),
			'distribution_complete' => array (
					'help' => 'True if daily distribution is complete',
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'False',
					'required' => 1,
			),			
	); /* fields */	

} /* class */
?>
