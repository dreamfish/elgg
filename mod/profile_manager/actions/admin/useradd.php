<?php

	/**
	 * Elgg add action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	admin_gatekeeper(); // Only admins can make someone an admin
	action_gatekeeper();
	
	// Get variables
	global $CONFIG;
	$username = get_input('username');
	$password = get_input('password');
	$password2 = get_input('password2');
	$email = get_input('email');
	$name = get_input('name');
	
	$admin = get_input('admin');
	if (is_array($admin)) $admin = $admin[0];
	
	$notify = get_input('notify', false);
	if (is_array($notify)) $notify = $notify[0];
	
	$use_default_access = get_input('use_default_access', false);
	if (is_array($use_default_access)) $use_default_access = $use_default_access[0];
	
	$custom_profile_fields = get_input("custom_profile_fields"); 
	// For now, just try and register the user
	try {
		if (
			(
				(trim($password)!="") &&
				(strcmp($password, $password2)==0) 
			) &&
			($guid = register_user($username, $password, $name, $email, true))
		) {
			$new_user = get_entity($guid);
			if (($guid) && ($admin))
				$new_user->admin = 'yes';
			
			$new_user->admin_created = true;
			
			if(!empty($notify)){
				notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('useradd:subject'), sprintf(elgg_echo('useradd:body'), $name, $CONFIG->site->name, $CONFIG->site->url, $username, $password));
			}
			
			// add all optional extra userdata
			if(is_array($custom_profile_fields)){
				foreach($custom_profile_fields as $metadata_name => $metadata_value){
					if(!empty($metadata_value) || $metadata_value === 0){
						if(!empty($use_default_access)){
							// use create_metadata to listen to ACCESS_DEFAULT
							create_metadata($new_user->guid, $metadata_name, $metadata_value, "", $new_user->guid, ACCESS_DEFAULT);
						} else {						
							$new_user->$metadata_name = $metadata_value;
						}
					}
				}
			}
			
			system_message(sprintf(elgg_echo("adduser:ok"),$CONFIG->sitename));
		} else {
			register_error(elgg_echo("adduser:bad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}

	forward($_SERVER['HTTP_REFERER']);
	exit;
?>