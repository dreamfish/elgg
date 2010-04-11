<?php
	/**
	 * Admin password reset.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	global $CONFIG;
	
	// block non-admin users
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	
	if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
	{
		$password = generate_random_cleartext_password();
		
		$obj->salt = generate_random_cleartext_password(); // Reset the salt
		$obj->password = generate_user_password($obj, $password);
		
		if ($obj->save())
		{
			system_message(elgg_echo('admin:user:resetpassword:yes'));
			
			notify_user($obj->guid, $CONFIG->site->guid, elgg_echo('email:resetpassword:subject'), sprintf(elgg_echo('email:resetpassword:body'), $obj->username, $password), NULL, 'email');
		} else
			register_error(elgg_echo('admin:user:resetpassword:no'));
	}
	else
		register_error(elgg_echo('admin:user:resetpassword:no'));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>