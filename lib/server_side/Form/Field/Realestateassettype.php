<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Realestateassettype extends Root {
	function __construct($opts = array()) {
		/* define options and their defaults */
		$this->setOptionDefault ( 'name', '' );
		$this->setOptionDefault ( 'size', 1);
		$this->setOptionDefault ( 'value', '' );
		$this->setOptionDefault ( 'multiple', '' );
		$this->setOptionDefault ( 'required', '' );
		$this->setOptionDefault ( 'position', 'bottom' ); /* possible values bottom, right */
		$this->setOptionDefault ( 'dataClassName', '' );
		
		/* update options with input */
		parent::__construct ( $opts );
	}
	
	public function show() {
		/* return with cover */
		return (new Html_FormFieldComponentCover ( array (
				'position' => $this->getOption ( 'position' ),
				'content' => '<b>Current value : ' . $this->getOption('value') 
					. '</b><div class=""><ol>' 
					. $this->createTreeView(
						json_decode(file_get_contents('/hybr/websites/ajpm/data/real_estate_asset_type.json'), true),
						'#'
					) . '</ol></div>'
		) ))->get ();
	}
	
	public function showJsTree() {
		$contentHtmlTag2 = new Html_Tag(array(
			'tag' => 'script',
			'content' => '$(function() {
				$("#' . $this->getOption( 'name' ) . '").jstree({core : {
					"data" : '.file_get_contents("/hybr/websites/ajpm/data/real_estate_asset_type.json").',
  					"plugins" : [ "checkbox" ],
				 	"rules" : { "multiple" : false }
				}});
			});',
		));
	
		/* return with cover */
		return (new Html_FormFieldComponentCover ( array (
			'position' => $this->getOption ( 'position' ),
			'content' => '<div id="'.$this->getOption ( 'name' ).'"></div>' . $contentHtmlTag2->get() 
		) ))->get ();
	}
} /* class */
