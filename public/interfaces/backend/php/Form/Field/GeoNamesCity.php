<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_GeoNamesCity extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                /* update options with input */
                parent::__construct($opts);

                $this->setOptionDefault('class', 'jpm_geonames_city');
                $this->setOptionDefault('type', 'text');
        }

	public function show() {
		$inputHtmlTag = new Html_Input(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),
			'class' => $this->getOption('class'),
		));

		/* return with cover */
		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $inputHtmlTag->get(),
		)))->get();

	}

} /* class */
