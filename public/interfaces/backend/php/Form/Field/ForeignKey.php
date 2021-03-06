<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_ForeignKey extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_search_fields', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');

                /* update options with input */
                parent::__construct($opts);
                $this->setOptionDefault('class', 'jpm_foreign_key_input');
        }

	public function show() {
		$inputHtmlTag = new Html_Input(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'class' => $this->getOption('class'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),

			'jpm_foreign_collection' => $this->getOption('jpm_foreign_collection'),
			'jpm_foreign_search_fields' => $this->getOption('jpm_foreign_search_fields'),
			'jpm_foreign_title_fields' => $this->getOption('jpm_foreign_title_fields'),
		));


		$valueInstance = new Form_Field_Value(array(
                        'value' => $this->getOption('value'),
                        'jpm_foreign_collection' => $this->getOption('jpm_foreign_collection'),
                        'jpm_foreign_title_fields' => $this->getOption('jpm_foreign_title_fields'),
                ));

		/* return with cover */
		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $inputHtmlTag->get() . $valueInstance->get(),
		)))->get();

	}

} /* class */
