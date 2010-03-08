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

	
//	gatekeeper();
//	set_context('pages');

	// Get fields
	$welcome_subj = get_input('email_welcome_subj');
	$welcome_body = get_input('email_welcome');
	$s_guid = get_input('s_guid');
	$b_guid = get_input('b_guid');
 	$welcome_subject_key = "email:validate:success:subject";
	$welcome_body_key = "email:validate:success:body";
	//function elgg_echo($message_key, $language = "") {
                                
	set_new_msg($welcome_subject_key,$welcome_subj,$s_guid);
	set_new_msg($welcome_body_key,$welcome_body,$b_guid);
		
	system_message("New welcome email message saved!");

	forward($vars['url'] . "mod/dreamfish_admin/configure_welcome_email.php");

function set_new_msg($message_key, $message_value,$guid) 
{
	// Load configuration
	global $CONFIG;

        $language = get_language();

	$object = null;
	if ( ($guid != 0) && ($guid != null) )
	{
		$object = get_entity((int)$guid);
	}
	else
	{
		$object = new ElggObject();
		$object->subtype = "df_custom_msg";
		$object->access_id = 1;
		$object->title= $message_key;
		$object->set("language",$language);
	}
			
	$object->description = $message_value;
 
	$object->save();

	update_language_file($message_key, $message_value,$language);
}

	
	//system_message(elgg_echo("pages:saved"));
			
	//add to river
	//add_to_river('river/object/page/create','create',$_SESSION['user']->guid,$page->guid);
		
	//register_error(elgg_echo('pages:notsaved'));

	//register_error(elgg_echo("pages:noaccess"));
	

	// Forward to the user's profile
	//forward($page->getUrl());
?>
