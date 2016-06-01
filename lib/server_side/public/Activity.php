<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Activity extends Base {
	function __construct() {
		$this->collectionName = 'activity';
	} /* __construct */
	public $fields = array (
			'title' => array (
					'show_in_list' => 1,
			),
			'about' => array(),
			'step' => array (
					'type' => 'container',
					'required' => 1,
					'help' => 'Step for data entry and save the information',
					'fields' => array (
							'name' => array(),
							'about' => array(),
							'field' => array (
									'type' => 'container',
									'required' => 1,
									'help' => 'Step for data entry and save the information',
									'fields' => array (
											'name' => array (
												'type' => 'foreign_key',
												'foreign_collection' => 'database_collection_field',
												'foreign_search_fields' => 'collection,parent_field,name',
												'foreign_title_fields' => 'collection,parent_field,name',
												'required' => 1
											)
									) /* field fields */
						), /* field */ 
					), /* step fields */ 
			),/* step */ 
	); /* fields */
	
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		$rStr .= '<br /><div class="ajpmTabs">';
		$rStr .= '<h2>'.$doc['title'].'</h2>';
		$i = 1;
		$rStr .= '<ul>';
		foreach ($doc['step'] as $step) {
			$rStr .= '<li>';
			$rStr .= '<a href="#i'.$i.'">'.$i . '. ' . $step['name'].'</a>';
			$rStr .= '</li>';
			$i++;
		}
		$rStr .= '</ul>';
		
		
		$i = 1;
		foreach ($doc['step'] as $step) {
			$rStr .= '<div id="i'.$i.'">';
			$rStr .= $step['about'];
			foreach($step['field'] as $field) {
				$rStr .= $step['name'];
			}
			$rStr .= '</div>';
			$i++;
		}
		
		$rStr .= '</div>'; /* tabsImageSlider */	
		return $rStr;
	}
} /* class */
?>
