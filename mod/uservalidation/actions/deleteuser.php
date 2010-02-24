<?php
	/**
	 * User validation plugin.
	 * Delete User.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	admin_gatekeeper();
	
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	
	if (($user) && ($user instanceof ElggUser))
	{
		if ($user->delete())
		{
			system_message(elgg_echo('admin:user:delete:yes'));
		}
		else
		{
			register_error(elgg_echo('admin:user:delete:no'));
		}
	}
	else
	{
		register_error(elgg_echo('admin:user:delete:no'));
	}	
	access_show_hidden_entities($access_status);
	forward($_SERVER['HTTP_REFERER']);
	exit;
	
?>