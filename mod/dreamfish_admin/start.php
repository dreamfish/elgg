<?php

/**
 * This plugin adds Dreamfish specific functionality. It allows
 * email messages to be customized.
 * 
 * The plugin adds an additional admin entry in the admin navigation
 * on the left. Clicking on it will show all possible customizable
 * emails. The admin can edit these and save them.
 * 
 * WARNING:
 * Saving happens by overwriting the original language file.
 * This is dangerous because if there's a problem with the new
 * value the whole file might become corrupt, even causing the
 * whole application to stop working!
 * 
 * To add a new email message to the list of customizable messages:
 * <lu>
 * <li>Edit <elgg_plugin_path>dreamfish_admin/views/default/dreamfish_admin/list.php
 *   and add a new link to the new message there</li>
 * <li>Create a new file "configure_<your_new_message>.php" in dreamfish_admin/views/default
 *   which actually just calls the form:</li>
 * <li>Create a new file "dreamfish_admin/views/default/dreamfish_admin/forms/<your_new_message>.php",
 *   which is the form that displays the data</li>
 * <li>Create a new file "dreamfish_admin/actions/<your_new_message>.php" which does
 *   the saving. Copy and paste from existing actions/forms</li>
 * </ul>
 * 
 * This could be simplified as the interface looks always rather the same,
 * by just having only one form, one page and one action which renders data and
 * saves the file according to parameters.
 */

function dreamfish_admin_init() {

	global $CONFIG;
	register_action('dreamfish_admin/email_confirm', false, $CONFIG->pluginspath . "dreamfish_admin/actions/email_confirm.php");
	register_action('dreamfish_admin/welcome', false, $CONFIG->pluginspath . "dreamfish_admin/actions/welcome.php");
	register_action('dreamfish_admin/new_friend', false, $CONFIG->pluginspath . "dreamfish_admin/actions/new_friend.php");
	register_action('dreamfish_admin/new_message', false, $CONFIG->pluginspath . "dreamfish_admin/actions/new_message.php");	
	
	//register_notification_handler('email','df_notification_handler');	
}

/*
function df_notification_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL)
{
	error_log("DF nothandler CALLED");
	error_log("DATA: from" . $from->name . " - to: " . $to->name . " - subject: " . $subject . " - message: " . $message );
	return true;
}
*/

function dreamfish_admin_pagesetup()
{
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('dreamfish_admin:admin_title'), $CONFIG->wwwroot . 'mod/dreamfish_admin/admintasks.php');
	}
}

/**
 * When an (admin) user is updating any customized email message,
 * this function is doing the work
 * 
 * @param $message_key: The key for the language file (e.g. email:validate:body )
 * @param $message_value: The new value for that key
 * @param $plugin_name: The plugin name of which we are updating the language file
 */
function set_new_notification_message($message_key, $message_value,$plugin_name) 
{
	//If the $message_key is invalid, nothing to do
	if ( ($message_key === null) || ($message_key == ""))
	{
		error_log("dreamfish_admin::start.php::set_new_notification_message: message_key invalid!");
		return false;
	}
		
	//Not going to update an entry with an invalid message
	if ( ($message_value === null) || ($message_value == ""))
	{
		error_log("dreamfish_admin::start.php::set_new_notification_message: message_value invalid!");
		return false;
	}

	// Load configuration
	global $CONFIG;
	
	//get the current language, exchanging values in language file dependent on language
    $language = get_current_language();
    
    //construct the path to the language file
    //if there is a plugin name prepend /mod/<plugin-name>/ to the language file name
	$root = $CONFIG->path;
	if ($plugin_name !== null)
		$root =  $CONFIG->pluginspath . $plugin_name . "/";
	
	$language_file = $root . "languages/" . $language . ".php";
	
	if (! file_exists($language_file)) {
		error_log(elgg_echo('dreamfish_admin:file_not_saved'));
		error_log(elgg_echo('dreamfish_admin:lang_not_found'));
		register_error(elgg_echo('dreamfish_admin:file_not_saved'));
		register_error(elgg_echo('dreamfish_admin:lang_not_found'));
		forward($_SERVER['HTTP_REFERER']);
		return false;
	}
	
	//read the file into a string
	$content = file_get_contents($language_file, FILE_TEXT);
	//the message value shall not contain undesired characters	
	$message_value = df_sanitize_string($message_value);
	
	//we exchange the values through a regular expression
	$pattern = "/" . $message_key . "(.*?)(\"|'),/s";
	$replacement =  $message_key . "' => \"" . $message_value . "\",";
	//error_log("pattern: " .$pattern . " replacement: " . $replacement);
    
    //apply the regex
	$new_file = preg_replace($pattern, $replacement, $content);
	//write new language file
	file_put_contents($language_file, $new_file);
	
	return true;
}

//is there somewhere a function for this?
/**
 * This method checks if the string for the language file contains
 * "" and '', as they might prevent the correct working of the language file
 * (Through the use of rich text editors like CKEditor, any character 
 * can be passed to the language file, which is a PHP file and must
 * follow syntax ).
 */
function df_sanitize_string($string)
{
	$replaced = $string;
	if (strpos($string, "\""))
		$replaced = preg_replace("/\"/", "\\\"", $string);
	if (strpos($string, "',"))
		$replaced = preg_replace("/',/", "' ,'", $replaced);
	
	return $replaced;
}

/**
 * Storage class for all those keys for language files that
 * we are going to use.
 */
global $LANG_KEYS;

$LANG_KEYS = new stdClass;

$LANG_KEYS->confirm_body_key = 'email:validate:body';
$LANG_KEYS->confirm_subj_key = 'email:validate:subject';

$LANG_KEYS->newfriend_body_key = 'friend:newfriend:body';
$LANG_KEYS->newfriend_subj_key = 'friend:newfriend:subject';

$LANG_KEYS->welcome_body_key = 'email:validate:success:body';
$LANG_KEYS->welcome_subj_key = 'email:validate:success:subject';

$LANG_KEYS->newmessage_body_key = 'messages:email:body';	
$LANG_KEYS->newmessage_subj_key = 'messages:email:subject';



register_elgg_event_handler('init','system','dreamfish_admin_init');
register_elgg_event_handler('pagesetup','system','dreamfish_admin_pagesetup');
?>
