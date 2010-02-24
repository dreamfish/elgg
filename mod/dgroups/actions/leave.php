<?php
	/**
	 * Leave a dgroup action.
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
	$dgroup_guid = get_input('dgroup_guid');
	
	$user = NULL;
	if (!$user_guid) $user = $_SESSION['user'];
	else
		$user = get_entity($user_guid);
		
	$dgroup = get_entity($dgroup_guid);
	
	if (($user instanceof ElggUser) && ($dgroup instanceof ElggGroup))
	{
		if ($dgroup->getOwner() != $_SESSION['guid']) {
			if ($dgroup->leave($user))
				system_message(elgg_echo("dgroups:left"));
			else
				register_error(elgg_echo("dgroups:cantleave"));
		} else {
			register_error(elgg_echo("dgroups:cantleave"));
		}
	}
	else
		register_error(elgg_echo("dgroups:cantleave"));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>