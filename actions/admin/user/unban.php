<?php
	/**
	 * Elgg ban user
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	// block non-admin users
	admin_gatekeeper();
	action_gatekeeper();
	
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	
	if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
	{
		// Now actually disable it
		if ($obj->unban())
			system_message(elgg_echo('admin:user:unban:yes'));
		else
			register_error(elgg_echo('admin:user:unban:no'));
	}
	else
		register_error(elgg_echo('admin:user:unban:no'));
		
	access_show_hidden_entities($access_status);
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>