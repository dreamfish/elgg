<?php
	/**
	 * User validation plugin.
	 * Activate User.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	// Admins only
	admin_gatekeeper();
	// Show hidden (not enabled) entities
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	// Get the user guid
	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	
	// Check, if it is an ElggUser Object
	if (($user) && ($user instanceof ElggUser))
	{
		// Activate User
		set_user_validation_status($user->guid, true, 'admin');
		$user->enable();
		system_message(elgg_echo('uservalidation:admin:confirm:success'));
		notify_user($user_guid, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:success:subject', $user->language), $user->name), sprintf(elgg_echo('uservalidation:success:body', $user->language), $user->name, $CONFIG->site->name, $CONFIG->site->url), NULL, 'email');
	}
	else
	{
		register_error(elgg_echo('uservalidation:admin:confirm:fail'));
	}	
	// Reset the hidden-status
	access_show_hidden_entities($access_status);
	// Forward to current page
	forward($_SERVER['HTTP_REFERER']);
	exit;
	
?>