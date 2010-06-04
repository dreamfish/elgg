<?php

	/**
	 * Elgg registration action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	global $CONFIG;
	
	action_gatekeeper();
	
	$df_announce_list_name = 'df_announce';
	$df_newproj_list_name  = 'df_new_projects';

	// Get variables
		$username = get_input('username');
		$password = get_input('password');
		$password2 = get_input('password2');
		$email = get_input('email');
		$name = get_input('name');
		$friend_guid = (int) get_input('friend_guid',0);
		$invitecode = get_input('invitecode');
		
		//Dreamfish hack:
		$df_announce_list = get_input('df_announce');
		$df_newproj_list = get_input('df_new_projects');
		
		//DF: see which newsletters have been selected
		$df_announce_list = get_input($df_announce_list_name);
		$df_newproj_list = get_input($df_newproj_list_name);		
		
		$newsletters = array();
		
		if ($df_announce_list != "")
		{
			array_push($newsletters , $df_announce_list_name); 
		}
		
		if ($df_newproj_list != "")
		{
			array_push($newsletters, $df_newproj_list_name);
		}
		
		
		
		$admin = get_input('admin');
		if (is_array($admin)) $admin = $admin[0];
		
		
		if (!$CONFIG->disable_registration)
		{
	// For now, just try and register the user
	
			try {

                               $valid = true;
 if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['_captcha'])) != $_SESSION['captcha']) {
        register_error("Invalid captcha");
        error_log("ACCOUNT: INVALID CAPTCHA");
        $valid = false;
}
if (!empty($_REQUEST['spam'])) {
        register_error("that field wasn't supposed to be filled out!");
        error_log("ACCOUNT: SPAM FIELD FILLED OUT");
        $valid = false;
}

				if (
					(	$valid &&
						(trim($password)!="") &&
						(strcmp($password, $password2)==0) 
					) &&
					($guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode))
				) {
					
					error_log("ACCOUNT: REGISTERING " . $username);
					
					$new_user = get_entity($guid);
					
					//add dreamfish newsletter registrations to metadata:
					if (count($newsletters) > 0)
					{					
						$new_user->newsletters = implode(',',$newsletters);
					}
					
					if (($guid) && ($admin))
					{
						admin_gatekeeper(); // Only admins can make someone an admin
						$new_user->admin = 'yes';
					}
					
					// Send user validation request on register only
					global $registering_admin;
					if (!$registering_admin)
						request_user_validation($guid);
					
					if (!$new_user->admin)
						$new_user->disable('new_user');	// Now disable if not an admin
					
					system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
				
				} else {
					register_error(elgg_echo("registerbad"));
				}
			} catch (RegistrationException $r) {
				register_error($r->getMessage());
			}
		}
		else
			register_error(elgg_echo('registerdisabled'));
			
		$qs = explode('?',$_SERVER['HTTP_REFERER']);
		$qs = $qs[0];
		$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
		
		forward($qs);

?>
