<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Organization extends Base {
	function __construct() {
		$this->collectionName = 'organization';
	} /* __construct */
	public $fields = array (
		'abbreviation' => array (
			'type' => 'string',
			'show_in_list' => 1,
		),
		'name' => array (
			'type' => 'string',
			'help' => 'Name of the organization',
			'show_in_list' => 1,
		),
		'statement' => array (
			'type' => 'string',
			'help' => 'Trade or copyright statement of the organization'
		),
		'parent_organization' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'organization',
			'foreign_search_fields' => 'abbreviation,name',
			'foreign_title_fields' => 'abbreviation,name',
			'show_in_list' => 1,
		),			
		'org_owner' => array (
			'help' => 'Person who will manage organization data',
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender',
			'show_in_list' => 1,
		),
		'web_domain' => array (
			'type' => 'container',
			'required' => 1,
			'show_in_list' => 1,
			'fields' => array (
				'name' => array (
					'show_in_list' => 1,
				)
			)
		),
		'web_site_content_type' => array (
			'help' => 'What type of website to setup?',
			'type' => 'list',
			'list_class' => 'WebSiteContentType',
			'input_mode' => 'selecting',
			'default' => 'Electronic Commerce',
		),
		'web_site_logo_file_name' => array (
			'type' => 'file_list'
		),
		'web_site_theme' => array (
			'help' => 'Color scheme of admin site',
			'type' => 'list',
			'list_class' => 'WebPageTheme',
			'input_mode' => 'selecting',
			'default' => 'start'
		),
		'web_site_theme_2' => array (
			'help' => 'Color scheme for main home page. Colors are primary, accent, warn, background, darkness',
			'type' => 'list',
			'list_class' => 'WebPageTheme2',
			'input_mode' => 'selecting',
			'default' => 'start'
		),
	); /* fields */
	
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
	
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['name'] . '</h2></td></tr>';
		
		$rStr .= '<tr class="ui-widget-content"><td class="jpmContentPadding" colspan="2">' . $doc ['statement'] . '</td></tr>';
	
		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Abbreviation";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['abbreviation'];
		$rStr .= '</td>';
		$rStr .= '</tr>';
		if ($doc ['web_site_owner'] != "") {
			$owner = $_SESSION ['mongo_database']->person->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['web_site_owner']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Owner";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$personClass =  new public_Person();
			$personClass->record = $owner; 
			$rStr .= $personClass->getOfficialFullName();
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}				
		
		if ($doc ['parent_organization'] != "") {
			$parentOrg = $_SESSION ['mongo_database']->organization->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['parent_organization']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Parent organization"; 
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  $parentOrg['name'];
			$rStr .= '</td>';			
			$rStr .= '</tr>';
		}
		foreach ($doc ['web_domain'] as $domain) {
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Domain";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .= $domain['name'];
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}
		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Theme";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['web_site_theme'];
		$rStr .= '</td>';
		$rStr .= '</tr>';	
		
		if ($doc ['web_site_home_page'] != "") {
			$webPage = $_SESSION ['mongo_database']->web_page->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['web_site_home_page']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Home Page";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  $webPage['title'];
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}
		
		$rStr .= '</table>';
		return $rStr;
	}
	
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul>';
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li><a href="/organization/present?id=' . (string) ($doc['_id']) . '" >'
					. $doc['name'] . '</a>: ' . $doc['statement'];
			$parentOrganization = $this->getDocumentById('organization', $doc['parent_organization']);
			if ((string) ($parentOrganization ['_id']) != ( string ) ($parentOrganization ['_id'])) {
			$rStr .= ' | Patent Organization: <a href="/organization/present?id=' 
					. ( string ) ($parentOrganization ['_id']) . '" >' 
					. $parentOrganization ['name'] . '</a>';
			}
			$rStr .=  '</li>';
		}
		return $rStr . '</ul>';
	}	

	public function clients() {
		$rStr = '<ol>';
		$docCursor = $_SESSION ['mongo_database']->{$this->collectionName}->find ();	
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li>' . $doc['name'] . ' : ' . $doc['statement'];
			if (!empty($doc['web_domain'])) {
				$rStr .= '<ul>';
			}
			foreach ($doc['web_domain'] as $domain) {
				$rStr .= '<li><a target="_blank" href="http://'.$domain['name'].'">' . $domain['name'] . '</a></li>';
			}
			if (!empty($doc['web_domain'])) {
				$rStr .= '</ul>';
			}
			$parentOrganization = $this->getDocumentById('organization', (string) $doc['parent_organization']);
			if (!empty($parentOrganization)
				&& (string) ($parentOrganization ['_id']) != ( string ) ($doc ['_id'])) {
				$rStr .= ' | Patent Organization: ' . $parentOrganization ['name'] ;
			}
			$rStr .=  '</li>';
		}
		return $rStr . '</ol>';
	}
	
	/* public function getHomePageId () { */
	public function a($urlArgsArray) {
		$response = array();
		
		/**
		 * get the organization ID
		 */
		$organizationId = getParamValue('a', $urlArgsArray);
			
		$orgRecord = $_SESSION ['mongo_database']->{$this->collectionName}->findOne ( array (
			'_id' => new MongoId((string)(trim($organizationId)))
		) );
			
		$response['home_page_id'] = $orgRecord['web_site_home_page'];
			
		return json_encode($response);

	}
} /* class */
?>
