<?php
	/**
	 * Action for changing a user's default access level
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
	
	if ($CONFIG->allow_user_default_access) {

		gatekeeper();
		
		$default_access = get_input('default_access');
		$user_id = get_input('guid');
		$user = "";
		
		if (!$user_id)
			$user = $_SESSION['user'];
		else
			$user = get_entity($user_id);
			
		if ($user)
		{
			$current_default_access = $user->getPrivateSetting('elgg_default_access');
			if ($default_access != $current_default_access)
			{
				if ($user->setPrivateSetting('elgg_default_access',$default_access))
					system_message(elgg_echo('user:default_access:success'));
				else
					register_error(elgg_echo('user:default_access:fail'));
			}
		}
		else
			register_error(elgg_echo('user:default_access:fail'));
	}
	
	//forward($_SERVER['HTTP_REFERER']);
	//exit;
?>