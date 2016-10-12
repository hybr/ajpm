<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_WorkerSalaryComponent extends Base {
	function __construct() {
		$this->collectionName = 'worker_salary_component';
	} /* __construct */

	private $startDate = null;
	private $endDate = null;
	private $showTransections = false;
	public $fields = array (
                'component' => array (
                        'type' => 'string' ,
                        'required' => 1,
			'show_in_list' => 1,
                ),
		'payslip_text' => array(),
		'detail' => array(),
		'country' => array(
			'help' => 'Country where this component is applicable. If this is at global level then keep it empty',
			'show_in_list' => 1,
		),
		'state_or_province' => array(
			'help' => 'State or province where this component is applicable. If this is at country level then keep it empty.',
			'show_in_list' => 1,
		),
		'district_or_county' => array(
			'help' => 'District or county where this component is applicable. If this is at country/state/province level then keep it empty.',
			'show_in_list' => 1,
		),
                'applicable' => array (
                        'help' => 'If applicable for salary calculation any more. Sometime goverment changes rules, so mark it as false and create new one',
                        'type' => 'list',
                        'list_class' => 'Boolean',
                        'input_mode' => 'clicking',
                        'default' => 'True',
                        'required' => 1,
			'show_in_list' => 1,
                ),
                'applicable_start_date' => array (
                        'help' => 'Not used until applicable is marked as false',
                        'type' => 'date',
                ),
                'applicable_last_date' => array (
                        'help' => 'Not used until applicable is marked as false',
                        'type' => 'date',
                ),
		'currency' => array (
			'help' => 'Currency used for amount in various part of this record',
			'type' => 'currency',
			'required' => 1,
			'default' => 'INR',
			'searchable' => 1,
		),
                'percent_of_base_salary' => array (
                        'type' => 'number',
			'default' => 0,
                ),
                'goverment_approved_minimum_amount' => array (
                        'type' => 'number',
			'default' => 0,
                ),
                'goverment_approved_minimum_amount_duration' => array (
                        'type' => 'list',
                        'list_class' => 'TimeRepeatFrequency',
                        'input_mode' => 'selecting',
                        'default' => 'Day',
                ),
                'applicable_to_tax' => array (
                        'help' => 'If applicable for goverment tax deduction',
                        'type' => 'list',
                        'list_class' => 'Boolean',
                        'input_mode' => 'clicking',
                        'default' => 'False',
                        'required' => 1,
                ),
                'tax_deduction_percent' => array (
			'help' => 'Keep this zero if no tax deduction and 100 if it is fully deductible',
                        'type' => 'number',
			'default' => 0,
                ),
                'tax_deduction_amount' => array (
			'help' => 'Keep this zero if no tax deduction',
                        'type' => 'number',
			'default' => 0,
                ),
                'tax_exempt_percent' => array (
			'help' => 'Keep this zero if no tax exempt and 100 if it is fully exempt',
                        'type' => 'number',
			'default' => 0,
                ),
                'tax_exempt_amount' => array (
			'help' => 'Keep this zero if no tax exempt',
                        'type' => 'number',
			'default' => 0,
                ),
                'applicable_to_pension_fund' => array (
                        'help' => 'If applicable for pension fund contribution',
                        'type' => 'list',
                        'list_class' => 'Boolean',
                        'input_mode' => 'clicking',
                        'default' => 'False',
                        'required' => 1,
                ),
                'pension_fund_employer_contribution_percent' => array (
			'help' => 'Keep this zero if no pension fund contribution by employer',
                        'type' => 'number',
			'default' => 0,
                ),
                'pension_fund_worker_contribution_percent' => array (
			'help' => 'Keep this zero if no pension fund contribution by worker',
                        'type' => 'number',
			'default' => 0,
                ),
                'applicable_to_gratuity' => array (
                        'help' => 'If applicable for gratuity fund contribution',
                        'type' => 'list',
                        'list_class' => 'Boolean',
                        'input_mode' => 'clicking',
                        'default' => 'False',
                        'required' => 1,
                ),
                'gratuity_fund_contribution_percent' => array (
			'help' => 'Keep this zero if no gratuity fund contribution',
                        'type' => 'number',
			'default' => 0,
                ),
	); /* fields */

} /* class */
?>
