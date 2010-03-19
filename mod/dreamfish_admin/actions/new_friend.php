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
	$new_friend_subj = get_input('new_friend_subj');
	$new_friend_body = get_input('new_friend_body');
	
	$plugin_name = null;
	
	//function elgg_echo($message_key, $language = "") {
    global $LANG_KEYS;
	set_new_notification_message($LANG_KEYS->newfriend_subj_key,$new_friend_subj,$plugin_name);
	set_new_notification_message($LANG_KEYS->newfriend_body_key,$new_friend_body,$plugin_name);
		
	system_message(elgg_echo('dreamfish_admin:new_newfriend_msg_ok'));

	forward($vars['url'] . "mod/dreamfish_admin/configure_newfriend_email.php");


//function set_new_msg_old($message_key, $message_value,$guid) 
//{
//	// Load configuration
//	global $CONFIG;
//
//        $language = get_language();
//
//	$object = null;
//	if ( ($guid != 0) && ($guid != null) )
//	{
//		$object = get_entity((int)$guid);
//	}
//	else
//	{
//		$object = new ElggObject();
//		$object->subtype = "df_custom_msg";
//		$object->access_id = 1;
//		$object->title= $message_key;
//		$object->set("language",$language);
//	}
//	$object->description = $message_value;
// 
//	$object->save();
//}
			
	//add to river
	//add_to_river('river/object/page/create','create',$_SESSION['user']->guid,$page->guid);
		
	//register_error(elgg_echo('pages:notsaved'));

	//register_error(elgg_echo("pages:noaccess"));
	

	// Forward to the user's profile
	//forward($page->getUrl());
?>
