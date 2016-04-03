<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Select extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('multiple', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */
                $this->setOptionDefault('dataClassName', '');

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$dataClassName = $this->getOption('dataClassName');
		if ($dataClassName == '') { return 'data source mising'; }
		$dataClassInstance = new $dataClassName();

		/* option are content  of select tag */
		$selectOptionsList = '';
		$currentValue = $this->getOption('value');
                foreach ( $dataClassInstance->getTable () as $r ) {
			$selected = '';
                        if ($r['value'] == $currentValue) {
                                $selected = 'selected';
                        }
			$contentHtmlTag = new Html_SelectOption(array(
				'value' => $r['value'],
				'content' => $r['title'],
				'selected' => $selected,
			));
			$selectOptionsList .= $contentHtmlTag->get();
                } /* foreach ( $dataClassInstance->getTable () as $r ) */

		/* select */
                if($this->getOption('multiple') == 1) {
			$this->setOption('multiple', 'multiple');
		}
		$selectHtmlTag = new Html_Select(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'multiple' => $this->getOption('multiple'),
                	'required' => $this->getOption('required'),
			'content' => $selectOptionsList,
		));

		/* return with cover */
		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $selectHtmlTag->get(),
		)))->get();

	}

} /* class */
