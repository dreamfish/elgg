<?php
	global $CONFIG;

	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	$code = sanitise_string(get_input('c'));
	
	if ( ($code) && ($user) )
	{
		if (siteaccess_validate_email($user_guid, $code)) {
			system_message(elgg_echo('siteaccess:confirm:success'));
                        siteaccess_notify_user($user, 'validated');
			
		} else
			register_error(elgg_echo('siteaccess:confirm:fail'));
	}
	else
		register_error(elgg_echo('siteaccess:confirm:fail'));
		
	forward();
	exit;

?>
