<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_OrganizationTeamMemberLevel extends Base {
	function __construct() {
		$this->collectionName = 'organization_team_member_level';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'string',
			'help' => 'Name of member level in the team',
			'show_in_list' => 1,
			'required' => 1
		) 
	); /* fields */
} /* class */
?>
