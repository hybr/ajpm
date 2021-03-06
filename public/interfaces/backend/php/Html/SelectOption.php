<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class Html_SelectOption extends Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('value', '');
		$this->setOptionDefault('selected', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can not be changed */
		$this->setOptionDefault('tag', 'option');
		$this->setOptionDefault('class', 'jpm-html-select-option ui-menu-item');
	}

} /* class */
?>
