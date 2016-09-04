<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_BankAccount extends Base {
		
	function __construct() {
		$this->collectionName = 'bank_account';
	} /* __construct */

	public $fields = array (
                'org_holder' => array (
			'help' => 'If this account is hold by an organization, enter here',
                        'show_in_list' => 1,
                        'type' => 'foreign_key',
                        'foreign_collection' => 'organization',
                        'foreign_search_fields' => 'abbreviation,name',
                        'foreign_title_fields' => 'abbreviation,name'
                ),
                'holders' => array (
			'help' => 'Persons who hold this bank account. If an organization holds the account then persons are the directors of the organization',
                        'type' => 'container',
                        'show_in_list' => 1,
                        'fields' => array (
				'name' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'person',
					'foreign_search_fields' => 'name.first,name.middle,name.last,name.suffix',
					'foreign_title_fields' => 'name,gender',
					'show_in_list' => 1,
				),		
			),
		),
		'number' => array(),
		'ifsc' => array(),
		'opened_on' => array (
			'type' => 'date' ,
			'required' => 1,
		),
		'amount_currency' => array (
			'type' => 'currency',
			'required' => 1,
			'default' => 'INR',
		),
                'contacts' => array(
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
		'note' => array (
			'type' => 'string',
		),
	); /* fields */	

} /* class */
?>
