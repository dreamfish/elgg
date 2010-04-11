<?php
	/**
	* Profile Manager
	* 
	* Action to reset profile fields
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 
	action_gatekeeper();
	admin_gatekeeper();
	
	$type = get_input("type", "profile");

	if($type == "profile" || $type == "group"){
		if(delete_entities("object", "custom_" . $type . "_field")){
			system_message(elgg_echo("profile_manager:actions:reset:success"));
		} else {
			register_error(elgg_echo("profile_manager:actions:reset:error:unknown"));
		}
	} else {
		register_error(elgg_echo("profile_manager:actions:reset:error:wrong_type"));
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>