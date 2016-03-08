<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class Html_Textarea extends Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('rows', 3);
		$this->setOptionDefault('cols', 30);
		$this->setOptionDefault('position', '');
		$this->setOptionDefault('required', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can nt be changed */
		$this->setOptionDefault('tag', 'textarea');
	}

} /* class */
?>
