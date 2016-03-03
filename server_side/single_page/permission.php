<?php
/**
 * This framework will implement authorizations alsong with authentication.
 * Authorization will decides who can do what based on following information
 *
 * organization role = organization, team, level, location
 * person position = person, organization role
 * rbac permission = all, create, remove, update, list, show, present, present all
 * rbac rules =  organization role, rbac permission
 *
 * @param string $moduleNames
 * @param string $subTask
 * @return boolean
 */
function isAllowed($moduleNames, $subTask) {
	$_SESSION ['allowed_as'] = "NULL";
	
	/* creater is admin */
	/* echo "<pre>"; print_r($_SESSION['person']); echo '</pre>'; */
	$orgOwnerId = '';
	if (isset ( $_SESSION ['url_domain_org'] ['org_owner'] )) {
		$orgOwnerId = ( string ) $_SESSION ['url_domain_org'] ['org_owner'];
	}
	$orgCreatedId = '';
	if (isset ( $_SESSION ['url_domain_org'] ['created_by'] )) {
		$orgCreatedId = ( string ) $_SESSION ['url_domain_org'] ['created_by'];
	}
	if (isset ( $_SESSION ['person'] ) && isset ( $_SESSION ['person'] ['_id'] ) && ($orgOwnerId == ( string ) $_SESSION ['person'] ['_id'] || $orgCreatedId == ( string ) $_SESSION ['person'] ['_id'])) {
		$_SESSION ['allowed_as'] = "OWNER";
		return true;
	}
	
	/* permisson based approval */
	array_push ( $moduleNames, 'All' );
	if (isset ( $_SESSION ['person'] ) && isset ( $_SESSION ['person'] ['position'] ) && is_array ( $_SESSION ['person'] ['position'] )) {
		foreach ( $_SESSION ['person'] ['position'] as $position ) {
			
			if (isset ( $position ['role'] )) {
				/* we will get roles on person here */
				/* for each person role check in module role matches */
				/* ModuleNames is array of modules to be validated */
				/* First check rbac_rules and find roles and permissions for modules */
				if (is_array ( $moduleNames )) {
					$rulesCursor = $_SESSION ['mongo_database']->rbac_rule->find ( array (
							'module' => array (
									'$in' => $moduleNames 
							) 
					) );
					foreach ( $rulesCursor as $rule ) {
						if (( string ) ($position ['role']) == ( string ) ($rule ['organization_role']) && ($subTask == $rule ['permission'] || $rule ['permission'] == 'All')) {
							$_SESSION ['allowed_as'] = "AUTHORATIVE";
							return true;
						}
					}
				}
			} /* if */
		} /* foreach position */
	} /* if */
	
	/*
	 * echo "<pre>"; print_r($_SESSION['user']); echo '</pre>';
	 * echo "<pre>"; print_r($_SESSION); echo '</pre>';
	 */
	if (isset ( $_SESSION ['user'] ) && ! empty ( $_SESSION ['user'] ) && in_array ( strtolower ( $_SESSION ['url_action'] ), array (
			'public_query.php',
			'public_person',
			'public_user',
			'public_contact',
			'public_webpage',
			'public_organization',
			'public_itemcatalog',
			'public_item' 
	) )) {
		$_SESSION ['allowed_as'] = "USER";
		return true;
	}
	
	/* allow public tasks */
	if (in_array ( strtolower ( $_SESSION ['url_action'] ) . '-' . strtolower ( $_SESSION ['url_task'] ) . '-' . strtolower ( $_SESSION ['url_sub_task'] ), array (
			'public_user-login-all',
			'public_user-authenticate-all',
			'public_user-logout-all',
			'public_user-join-all',
			'public_user-register-all',
			'public_user-forgetpassword-all',
			'public_user-va-all',
			'public_user-sendactivationemail-all',
			'public_itemcatalog-presentall-all',
			'public_item-present-all',
			'public_shoppingcart-present-all',
			'public_shoppingcart-presentall-all',
			'public_webpage-present-all',
			'public_contact-presentall-all',
			'public_organization-clients-all',
			'public_familytree-presentall-all',
			'public_familytree-present-all' 
	) )) {
		$_SESSION ['allowed_as'] = "PUBLIC";
		return true;
	}
	
	return false;
}
