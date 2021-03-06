<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_FileUpload extends Base {
	function __construct() {
		$this->collectionName = 'file_upload';
	}
	public $fields = array (
			'file_name' => array (
					'type' => 'file',
					'required' => 1,
					'show_in_list' => 1 
			),
			'save_as_file_name' => array(
				'help' => 'New file name without extenssion. If empty it will take original file name.'
			)
	);
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
	
		$rStr .= '<table class="showTable">';	
		$rStr .= '<tr><th colspan="2"><h2>'.$doc['file_name'].'</h2></th></tr>';
		$rStr .= '<tr><td colspan="2"><img src="/file/'.(string)$_SESSION ['url_domain_org']['_id'].'/'.$doc['file_name'].'" /></td></tr>';
		$rStr .= '</table>';
		return $rStr;
	}
		
} /* class */
?>