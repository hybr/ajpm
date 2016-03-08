<?php

require_once DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Readonly extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('rows', '');
                $this->setOptionDefault('cols', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */
                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');
                $this->setOptionDefault('dataClassName', '');
                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {

		$valueInstance = new Form_Field_Value(array(
			'value' => $this->getOption('value'),
			'jpm_foreign_collection' => $this->getOption('jpm_foreign_collection'),
			'jpm_foreign_title_fields' => $this->getOption('jpm_foreign_title_fields'),
			'dataClassName' => $this->getOption('dataClassName'),
		));

		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $valueInstance->get(),
		)))->get();
	}

} /* class */
