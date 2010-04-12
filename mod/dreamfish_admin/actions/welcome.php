<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	
	admin_gatekeeper();

	// Get fields
	$welcome_subj = get_input('email_welcome_subj');
	$welcome_body = get_input('email_welcome');
	
	$plugin_name = "uservalidationbyemail";
                                
	//set_new_msg($welcome_subject_key,$welcome_subj,$s_guid);
	global $LANG_KEYS;
	set_new_notification_message($LANG_KEYS->welcome_subj_key,$welcome_subj,$plugin_name);
	set_new_notification_message($LANG_KEYS->welcome_body_key,$welcome_body,$plugin_name);
		
	system_message(elgg_echo('dreamfish_admin:new_welcome_msg_ok'));

	forward($vars['url'] . "mod/dreamfish_admin/configure_welcome_email.php");
	
	//system_message(elgg_echo("pages:saved"));
			
	//add to river
	//add_to_river('river/object/page/create','create',$_SESSION['user']->guid,$page->guid);
		
	//register_error(elgg_echo('pages:notsaved'));

	//register_error(elgg_echo("pages:noaccess"));

?>
