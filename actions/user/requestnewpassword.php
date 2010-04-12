<?php
	/**
	 * Action to request a new password.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	global $CONFIG;
	
	action_gatekeeper();
	
	$username = get_input('username');
	
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	$user = get_user_by_username($username);
	if ($user)
	{
		if ($user->validated) {
			if (send_new_password_request($user->guid))
				system_message(elgg_echo('user:password:resetreq:success'));
			else
				register_error(elgg_echo('user:password:resetreq:fail'));
		} else if (!trigger_plugin_hook('unvalidated_requestnewpassword','user',array('entity'=>$user))) {
        	// if plugins have not registered an action, the default action is to
        	// trigger the validation event again and assume that the validation
        	// event will display an appropriate message
			trigger_elgg_event('validate', 'user', $user);
        }
	}
	else
		register_error(sprintf(elgg_echo('user:username:notfound'), $username));
		
	access_show_hidden_entities($access_status);
	forward();
	exit;
?>