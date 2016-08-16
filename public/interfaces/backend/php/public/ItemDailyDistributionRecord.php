<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_ItemDailyDistributionRecord extends Base {
		
	function __construct() {
		$this->collectionName = 'item_daily_distribution_record';
	} /* __construct */
	public $fields = array (
		'payment_record' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'item_daily_distribution_payment',
				'foreign_search_fields' => 'paid_by,paid_amount',
				'foreign_title_fields' => 'paid_by,paid_amount',
				'show_in_list' => 1,
				'required' => 1,
		),
		'date' => array (
				'type' => 'date' ,
				'required' => 1,
				'show_in_list' => 1,
		),
		'distribution_time' => array (
				'type' => 'time' ,
				'required' => 1,
				'show_in_list' => 1,
		),
				
		'rate_amount' => array (
				'type' => 'number',
				'required' => 1,
		),
		'rate_amount_currency' => array (
				'type' => 'currency',
				'required' => 1,
				'default' => 'INR',
		),
		'rate_quantity' => array (
				'type' => 'string',
				'required' => 1,
		),
		'rate_quantity_unit' => array (
				'type' => 'string',
				'required' => 1,
		),
			
		'delivery_location' => array (
				'type' => 'string',
				'required' => 1,
		),			
		'delivery_location_code' => array (
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
				'type' => 'string' ,
				'required' => 1,				
		),
	); /* fields */	

} /* class */
?>
