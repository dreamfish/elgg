<?php

/**
 * Elgg registration action for uncaptcha.  Same as Elgg Core's except I disable the user before
 * requesting user validation to allow uncaptcha to enable that.
 * 
 * @package Uncaptcha
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt
 * @copyright Brett Profitt 2008
 * @link http://elgg.org/
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
global $CONFIG;

action_gatekeeper();

// Get variables
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$name = get_input('name');
$friend_guid = (int) get_input('friend_guid',0);
$invitecode = get_input('invitecode');

$admin = get_input('admin');
if (is_array($admin)) $admin = $admin[0];

// check the uncaptcha fields.
$code = get_input('uncaptcha_code');
$trick_field =  get_input(get_plugin_setting('trick_field_name', 'uncaptcha'));
$error = false;

if (!$code || $code != uncaptcha_generate_code()) {
	$error = elgg_echo('uncaptcha:register:bad_code');
}

if ($CONFIG->disable_registration) {
	$error = elgg_echo('registerdisabled');
}

if (!empty($trick_field)) {
	$error = elgg_echo('uncaptcha:register:non_empty_field');
}

if (!$error) {
	try {
		if (
			(
				(trim($password)!="") &&
				(strcmp($password, $password2)==0) 
			) &&
			($guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode))
		) {
			$hidden_entities = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			
			$new_user = get_entity($guid);
			if (($guid) && ($admin)) {
				admin_gatekeeper(); // Only admins can make someone an admin
				$new_user->admin = 'yes';
			}
			
			if (!$new_user->admin)
				$new_user->disable('new_user');	// Now disable if not an admin
				
			// Send user validation request on register only
			request_user_validation($guid);
			
			system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
			trigger_plugin_hook('uncaptcha:register_user', $new_user->type, $new_user, true);
			access_show_hidden_entities($hidden_entities);
			
			// special case to get the user value
			if ('*user_profile*' == ($forward_url = get_plugin_setting('register_success_forward', 'uncaptcha'))) {
				$forward_url = $_SESSION['user']->getURL();
			}
			forward($forward_url);
		} else {
			register_error(elgg_echo("registerbad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
		$qs = explode('?',$_SERVER['HTTP_REFERER']);
		$qs = $qs[0];
		$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
		
		forward($qs);
	}
} else {
	register_error($error);
	$qs = explode('?',$_SERVER['HTTP_REFERER']);
	$qs = $qs[0];
	$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
	
	forward($qs);
}