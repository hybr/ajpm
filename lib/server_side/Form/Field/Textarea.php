<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Textarea extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('rows', '');
                $this->setOptionDefault('cols', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$textareaHtmlTag = new Html_Textarea(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'rows' => $this->getOption('rows'),
                	'cols' => $this->getOption('cols'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),
		));

		/* return with cover */
		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $textareaHtmlTag->get(),
		)))->get();

	}

} /* class */
