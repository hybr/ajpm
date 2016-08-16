<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_ItemDailyDistributionException extends Base {
		
	function __construct() {
		$this->collectionName = 'item_daily_distribution_exception';
	} /* __construct */
	public $fields = array (
		'payment_record' => array (
				'help' => 'Type location or amount of payment record',
				'type' => 'foreign_key',
				'foreign_collection' => 'item_daily_distribution_payment',
				'foreign_search_fields' => 'paid_amount,delivery.location,delivery.location_code',
				'foreign_title_fields' => 'paid_amount,delivery',
				'show_in_list' => 1,
		),
		'start_date' => array (
				'type' => 'date' ,
				'required' => 1,
				'show_in_list' => 1,
		),
		'end_date' => array (
				'type' => 'date' ,
				'show_in_list' => 1,
		),
			
		/* things that can be part of ecception */
		'apply_sequence' => array (
				'help' => 'If there are more than one exceptions for day put order in which it needs to be applied',
				'type' => 'number',
		),			
		'new_distribution_time' => array (
				'help' => 'Does customer needs a new distribution time?',
				'type' => 'list',
				'list_class' => 'Boolean',
				'input_mode' => 'clicking',
				'default' => 'False',
				'required' => 1,
		),			
		'distribution_time' => array (
				'help' => 'Provide new value if change required',
				'type' => 'time' ,
		),				
		'rate_amount' => array (
				'help' => 'Provide new value if change required',
				'type' => 'number',
		),
		'rate_amount_currency' => array (
				'help' => 'Provide new value if change required',
				'type' => 'currency',
				'default' => 'INR',
		),
		'rate_quantity' => array (
				'help' => 'Provide new value if change required',
				'type' => 'string',
		),
		'rate_quantity_unit' => array (
				'help' => 'Provide new value if change required',
				'type' => 'string',
		),
			
		'delivery_location' => array (
				'help' => 'Provide new value if change required',
				'type' => 'string',
		),			

			
		'delivery_quantity' => array (
				'help' => 'Provide new value if change required',
				'type' => 'number',
		),
		'delivery_quantity_unit' => array (
				'help' => 'Provide new value if change required',
				'type' => 'string',
		),

		'instructions' => array (
				'help' => 'Provide reason why this exception needed',
				'type' => 'string' ,
		),
	); /* fields */	

} /* class */
?>
