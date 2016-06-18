<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class Html_FormFieldCover extends Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */

		/* update options with input */
		parent::__construct($opts);

		/* these options can not be changed */
		$this->setOptionDefault('tag', 'div');
		$this->setOptionDefault('class', 'jpm-html-form-field-cover');
	}

} /* class */
?>
