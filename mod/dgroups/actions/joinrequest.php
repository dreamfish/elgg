<?php
	/**
	 * User requests to join a closed dgroup.
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
	
	// If not a member of this dgroup
	if (($dgroup) && ($user) && (!$dgroup->isMember($user)))
	{
		// If open dgroup or invite exists
		if (
			($dgroup->isPublicMembership()) ||
			(check_entity_relationship($dgroup->guid, 'invited', $user->guid))
		)
		{
			if ($dgroup->join($user))
			{
				// Remove relationships
				remove_entity_relationship($dgroup->guid, 'invited', $user->guid);
				remove_entity_relationship($user->guid, 'membership_request', $dgroup->guid);
				
				// dgroup joined
				system_message(elgg_echo('dgroups:joined'));
				
				forward($dgroup->getURL());
				exit;
			}
			else
				system_message(elgg_echo('dgroups:cantjoin'));
		}
		else
		{
			// If join request not already made
			if (!check_entity_relationship($user->guid, 'membership_request', $dgroup->guid))
			{
				// Add membership requested
				add_entity_relationship($user->guid, 'membership_request', $dgroup->guid);
				
				// Send email
				if (notify_user($dgroup->owner_guid, $user->getGUID(), 
						sprintf(elgg_echo('dgroups:request:subject'), $user->name, $dgroup->name), 
						sprintf(elgg_echo('dgroups:request:body'), $dgroup->getOwnerEntity()->name, $user->name, $dgroup->name, $user->getURL(), "{$CONFIG->url}action/dgroups/addtodgroup?user_guid={$user->guid}&dgroup_guid={$dgroup->guid}"),
						NULL))
					system_message(elgg_echo("dgroups:joinrequestmade"));
				else
					register_error(elgg_echo("dgroups:joinrequestnotmade"));
			}
			else
				system_message(elgg_echo("dgroups:joinrequestmade"));
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>