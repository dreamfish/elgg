<?php
	/**
	 * Join a dgroup action.
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
		
	if (($user instanceof ElggUser) && ($dgroup instanceof ElggGroup))
	{
		if ($dgroup->isPublicMembership())
		{
			if ($dgroup->join($user))
			{
				system_message(elgg_echo("dgroups:joined"));
				
				// Remove any invite or join request flags
				remove_entity_relationship($dgroup->guid, 'invited', $user->guid);
				remove_entity_relationship($user->guid, 'membership_request', $dgroup->guid);
				
				// add to river
	        	add_to_river('river/dgroup/create','join',$user->guid,$dgroup->guid);
	        	
				forward($dgroup->getURL());
				exit;
			}
			else
				register_error(elgg_echo("dgroups:cantjoin"));
		}
		else
		{
			// Closed dgroup, request membership
			system_message(elgg_echo('dgroups:privatedgroup'));
			forward($CONFIG->url . "action/dgroups/joinrequest?user_guid=$user_guid&dgroup_guid=$dgroup_guid");
			exit;
		}
	}
	else
		register_error(elgg_echo("dgroups:cantjoin"));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>