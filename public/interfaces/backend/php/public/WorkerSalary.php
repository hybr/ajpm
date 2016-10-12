<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_WorkerSalary extends Base {
	function __construct() {
		$this->collectionName = 'worker_salary';
	} /* __construct */

	private $startDate = null;
	private $endDate = null;
	private $showTransections = false;
	public $fields = array (
                'worker' => array (
                        'type' => 'foreign_key',
                        'foreign_collection' => 'person',
                        'foreign_search_fields' => 'name.first,name.middle,name.last,name.suffix',
                        'foreign_title_fields' => 'name,gender',
                        'required' => 1,
                        'show_in_list' => 1,
                ),
                'paid_date' => array (
                        'type' => 'date' ,
                        'required' => 1,
                        'show_in_list' => 1,
                ),
                'salary_period_start' => array (
                        'type' => 'date' ,
                        'required' => 1,
                        'show_in_list' => 1,
                ),
                'salary_period_end' => array (
                        'type' => 'date' ,
                        'required' => 1,
                        'show_in_list' => 1,
                ),
                'component' => array (
                        'type' => 'foreign_key',
                        'foreign_collection' => 'worker_salary_component',
                        'foreign_search_fields' => 'country,state_or_province,district_or_county,component,payslip_text,detail',
                        'foreign_title_fields' => 'country,state_or_province,district_or_county,component,payslip_text',
                        'required' => 1,
                        'show_in_list' => 1,
                ),
                'amount' => array (
                        'type' => 'number',
                        'default' => 0,
                        'show_in_list' => 1,
                ),
                'amount_currency' => array (
                        'type' => 'currency',
                        'required' => 1,
                        'default' => 'INR',
                ),
		'note' => array(
                        'show_in_list' => 1,
		),
	); /* fields */

} /* class */
?>
