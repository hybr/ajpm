<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Currency extends Root {
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
		$currentValue = $this->getOption('value');
		$selectOptionsList = '';
		foreach ( json_decode ( file_get_contents("/hybr/websites/ajpm/data/currency.json"), TRUE ) as $key => $val ) {
			$selected = '';
			if ($key == $currentValue) {
				$selected = 'selected';
			}
			$contentHtmlTag = new Html_SelectOption ( array (
				'value' => $key,
				'content' => $val ['name'],
				'selected' => $selected 
			) );
			$selectOptionsList .= $contentHtmlTag->get ();
		} /* foreach ($jsonIterator as $key => $val) { */
		
		/* select */
		if ($this->getOption ( 'multiple' ) == 1) {
			$this->setOption ( 'multiple', 'multiple' );
		}
		$selectHtmlTag = new Html_Select ( array (
				'name' => $this->getOption ( 'name' ),
				'id' => $this->getOption ( 'name' ),
				'size' => 1,
				'multiple' => $this->getOption ( 'multiple' ),
				'required' => $this->getOption ( 'required' ),
				'content' => $selectOptionsList 
		) );
		
		/* return with cover */
		return (new Html_FormFieldComponentCover ( array (
				'position' => $this->getOption ( 'position' ),
				'content' => $selectHtmlTag->get () 
		) ))->get ();
	}
} /* class */
