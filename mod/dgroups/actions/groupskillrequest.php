<?php
	/**
	 * Delete a user request to join a closed dgroup.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$user_guid = get_input('user_guid', get_loggedin_userid());
	$dgroup_guid = get_input('dgroup_guid');
	
	$user = get_entity($user_guid);
	$dgroup = get_entity($dgroup_guid);
	
	// If join request made
			if (check_entity_relationship($user->guid, 'membership_request', $dgroup->guid))
			{
				remove_entity_relationship($user->guid, 'membership_request', $dgroup->guid);
				system_message(elgg_echo("dgroups:joinrequestkilled"));
			}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>