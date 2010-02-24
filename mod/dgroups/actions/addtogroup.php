<?php

	/**
	 * Add a user to a dgroup
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
	
	$user_guid = get_input('user_guid');
	if (!is_array($user_guid))
		$user_guid = array($user_guid);
	$dgroup_guid = get_input('dgroup_guid');
	
	if (sizeof($user_guid))
	{
		foreach ($user_guid as $u_id)
		{
			$user = get_entity($u_id);
			$dgroup = get_entity($dgroup_guid);
			
			if ( $user && $dgroup) {
				
				if (get_loggedin_userid() == $dgroup->owner_guid)
				{
					
					// If the dgroup is open or the user has requested membership
					if (
						(check_entity_relationship($user->guid, 'membership_request', $dgroup->guid)) ||
						($dgroup->isPublicMembership())
						)
					{
						
						if (!$dgroup->isMember($user))
						{
							// Remove relationships
							remove_entity_relationship($dgroup->guid, 'invited', $user->guid);
							remove_entity_relationship($user->guid, 'membership_request', $dgroup->guid);
						
							add_entity_relationship($user->guid, 'member', $dgroup->guid);
							
							// send welcome email
							notify_user($user->getGUID(), $dgroup->owner_guid, 
								sprintf(elgg_echo('dgroups:welcome:subject'), $dgroup->name), 
								sprintf(elgg_echo('dgroups:welcome:body'), $user->name, $dgroup->name, $dgroup->getURL()),
								NULL);
								
							system_message(elgg_echo('dgroups:addedtodgroup'));
						}
						else
							register_error(elgg_echo("dgroups:cantjoin"));
					}
					else
					{
						if ($user->isFriend())
						{
							
							// Create relationship
							add_entity_relationship($dgroup->guid, 'invited', $user->guid);
							
							// Send email
							if (notify_user($user->getGUID(), $dgroup->owner_guid, 
									sprintf(elgg_echo('dgroups:invite:subject'), $user->name, $dgroup->name), 
									sprintf(elgg_echo('dgroups:invite:body'), $user->name, $dgroup->name, "{$CONFIG->url}action/dgroups/join?user_guid={$user->guid}&dgroup_guid={$dgroup->guid}"),
									NULL))
								system_message(elgg_echo("dgroups:userinvited"));
							else
								register_error(elgg_echo("dgroups:usernotinvited"));
							
						}
						else
							register_error(elgg_echo("dgroups:usernotinvited"));
					}
				}
				else
					register_error(elgg_echo("dgroups:notowner"));
			}
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>