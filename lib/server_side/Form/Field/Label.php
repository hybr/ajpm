<?php

require_once DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Label extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('title', 'No Title');
                $this->setOptionDefault('name', 'NoFieldName');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'top'); /* possible values top, left */

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$contentHtmlTag = new Html_Tag(array(
                        'tag' => 'span',
                        'content' => (new TitleCreator(array( 'string' => $this->getOption('title'))))->get(),
                ));
		$titleText = trim($contentHtmlTag->get());
		if ($this->getOption('required') == 1) {
			$requiredHtmlTag = new Html_Tag(array(
				'tag' => 'span',
				'content' => ' *',
                        	'style' => 'color: red;',
			));
			$titleText .= ' ' . $requiredHtmlTag->get();
		}
		$labelHtmlTag = new Html_Tag(array(
			'tag' => 'label',
			'class' => 'ui-widget-header',
			'id' => $this->getOption('name'),
                        'style' => 'padding: 2px;',
			'content' => trim($titleText),
		));

                /* return with cover */
                return (new Html_FormFieldComponentCover(array(
                        'position' => $this->getOption('position'),
                        'content' => $labelHtmlTag->get(),
                )))->get();
	}

} /* class */
