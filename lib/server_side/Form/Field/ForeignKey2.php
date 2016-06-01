<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root2.php";
class Form_Field_ForeignKey2 extends Root2 {
	function __construct($opts = array()) {
		parent::__construct ( $opts );
		$this->setOptionDefault ( 'class', 'foreign_key-input' );
	}
	public function show() {
		$inputHtmlTag = new Html_Input2 ( $this->getAllOptions () );
		$valueInstance = new Form_Field_Value ( array (
				'value' => $this->getOption ( 'value' ),
				'jpm_foreign_collection' => $this->getOption ( 'jpm_foreign_collection' ),
				'jpm_foreign_title_fields' => $this->getOption ( 'jpm_foreign_title_fields' ) 
		) );
		
		/* return with cover */
		return (new Html_FormField ( array (
				'position' => $this->getOption ( 'position' ),
				'content' => $inputHtmlTag->get () . $valueInstance->get () 
		) ))->get ();
	}
} /* class */
