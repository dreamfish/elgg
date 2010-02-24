<?php
	/**
	 * User requests to join a closed group.
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
	$group_guid = get_input('group_guid');
	
	$user = get_entity($user_guid);
	$group = get_entity($group_guid);
	
	// If not a member of this group
	if (($group) && ($user) && (!$group->isMember($user)))
	{
		// If open group or invite exists
		if (
			($group->isPublicMembership()) ||
			(check_entity_relationship($group->guid, 'invited', $user->guid))
		)
		{
			if ($group->join($user))
			{
				// Remove relationships
				remove_entity_relationship($group->guid, 'invited', $user->guid);
				remove_entity_relationship($user->guid, 'membership_request', $group->guid);
				
				// Group joined
				system_message(elgg_echo('groups:joined'));
				
				forward($group->getURL());
				exit;
			}
			else
				system_message(elgg_echo('groups:cantjoin'));
		}
		else
		{
			// If join request not already made
			if (!check_entity_relationship($user->guid, 'membership_request', $group->guid))
			{
				// Add membership requested
				add_entity_relationship($user->guid, 'membership_request', $group->guid);
				
				// Send email
				if (notify_user($group->owner_guid, $user->getGUID(), 
						sprintf(elgg_echo('groups:request:subject'), $user->name, $group->name), 
						sprintf(elgg_echo('groups:request:body'), $group->getOwnerEntity()->name, $user->name, $group->name, $user->getURL(), "{$CONFIG->url}action/groups/addtogroup?user_guid={$user->guid}&group_guid={$group->guid}"),
						NULL))
					system_message(elgg_echo("groups:joinrequestmade"));
				else
					register_error(elgg_echo("groups:joinrequestnotmade"));
			}
			else
				system_message(elgg_echo("groups:joinrequestmade"));
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>