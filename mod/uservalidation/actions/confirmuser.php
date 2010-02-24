<?php
	/**
	 * User validation plugin.
	 * Confirm Registration.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	// Show hidden (not enabled) entities
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	// Get the user guid
	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	
	// Get the validation code and method
	$code = sanitise_string(get_input('c'));
	$method = sanitise_string(get_input('m'));
	
	// Check, if it is an ElggUser Object
	if (($code) && ($user) && ($user instanceof ElggUser))
	{
		// Check the validation code
		if (uservalidation_validate_code($user, $code)) 
		{
			// Activate User
			set_user_validation_status($user->guid, true, $method);
			$user->enable();
			system_message(elgg_echo('uservalidation:confirm:success'));
			notify_user($user_guid, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:success:subject'), $user->name), sprintf(elgg_echo('uservalidation:success:body'), $user->name, $CONFIG->site->name, $CONFIG->site->url), NULL, 'email');
		} 
		else  
		{
			register_error(elgg_echo('uservalidation:confirm:fail'));
		}
	}
	else 
	{
		register_error(elgg_echo('uservalidation:confirm:fail'));
	}
	// Reset the hidden-status
	access_show_hidden_entities($access_status);
	// Forward to index page
	forward($CONFIG->site->url);
	exit;

?>