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
	
	admin_gatekeeper();
	
	$dgroup_guid = get_input('dgroup_guid');
	$action = get_input('action');
	
	$dgroup = get_entity($dgroup_guid);
	
	if($dgroup){
		
		//get the action, is it to feature or unfeature
		if($action == "feature"){
		
			$dgroup->featured_dgroup = "yes";
			system_message(elgg_echo('dgroups:featuredon'));
			
		}
		
		if($action == "unfeature"){
			
			$dgroup->featured_dgroup = "no";
			system_message(elgg_echo('dgroups:unfeatured'));
			
		}
		
	}
	
	forward("pg/dgroups/world/");
	
?>