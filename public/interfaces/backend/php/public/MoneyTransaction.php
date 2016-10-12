<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_MoneyTransaction extends Base {
		
	function __construct() {
		$this->collectionName = 'money_transaction';
	} /* __construct */

	public $fields = array (
		'money' => array (
			'type' => 'list',
			'list_class' => 'MoneyTransactionType',
			'input_mode' => 'clicking',
			'default' => 'Money Paid',
			'required' => 1,
			'show_in_list' => 1,
		),			
		'date' => array (
			'type' => 'date' ,
			'required' => 1,
			'show_in_list' => 1,
		),
		'amount' => array (
			'help' => 'Advance amount paid to receive the item',
			'type' => 'number',
			'show_in_list' => 1,
			'required' => 1,
		),
		'amount_currency' => array (
			'type' => 'currency',
			'required' => 1,
			'default' => 'INR',
		),
		'quantity' => array (
			'help' => 'Number of items',
			'type' => 'number',
			'show_in_list' => 1,
		),
		'quantity_unit' => array (
			'show_in_list' => 1,
			'default' => 'Count',
		),
		'money_type' => array (
			'help' => 'Type of this transection with respect to money',
			'type' => 'foreign_key',
			'foreign_collection' => 'chart_of_accounts',
			'foreign_search_fields' => 'number,title,summary',
			'foreign_title_fields' => 'number,title',
		),
		'item_type' => array (
			'help' => 'Type of this transection with respect to item, product and service',
			'type' => 'foreign_key',
			'foreign_collection' => 'chart_of_accounts',
			'foreign_search_fields' => 'number,title,summary',
			'foreign_title_fields' => 'number,title',
		),
		'bank_account' => array (
			'help' => 'Bank account which is involved in this transection',
			'type' => 'foreign_key',
			'foreign_collection' => 'bank_account',
			'foreign_search_fields' => 'number,ifsc,type',
			'foreign_title_fields' => 'number,ifsc,type',
		),
		'item' => array (
			'help' => 'Item which is involved in this transection',
			'type' => 'foreign_key',
			'show_in_list' => 1,
			'foreign_collection' => 'item',
			'foreign_search_fields' => 'title,summary',
			'foreign_title_fields' => 'type,title',
		),
                'persons' => array (
                        'help' => 'Persons who helped or worked for this event',
                        'type' => 'container',
                        'fields' => array (
                                'name' => array (
                                        'type' => 'foreign_key',
                                        'foreign_collection' => 'person',
                                        'foreign_search_fields' => 'name.first,name.middle,name.last',
                                        'foreign_title_fields' => 'name,gender',
                                        'searchable' => 1,
                                ),
                        )
                ),
                'receipt' => array (
                        'type' => 'container',
                        'fields' => array (
                                'note' => array (),
                                'file_name' => array (
                                        'type' => 'file_list',
                                        'required' => 1,
                                ),
                        )
                ),
		'note' => array (
			'type' => 'string',
			'show_in_list' => 1,
		),
	); /* fields */	

} /* class */
?>
