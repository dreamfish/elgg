<?php
	/**
	 * Email user validation plugin.
	 * Non-admin or admin created accounts are invalid until their email address is confirmed. 
	 * 
	 * @package ElggUserValidationByEmail
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	function uservalidationbyemail_init()
	{
		global $CONFIG; 
		
		// Register actions
		register_action("email/confirm",true, $CONFIG->pluginspath . "uservalidationbyemail/actions/email/confirm.php");
		
		// Register hook listening to new users.
		register_elgg_event_handler('validate', 'user', 'uservalidationbyemail_email_validation');
	}

	/**
	 * Request email validation.
	 */
	function uservalidationbyemail_email_validation($event, $object_type, $object)
	{
		if (($object) && ($object instanceof ElggUser))
		{
			uservalidationbyemail_request_validation($object->guid);
		}
		
		return true;
	}
	
	/**
	 * Generate an email activation code.
	 *
	 * @param int $user_guid The guid of the user
	 * @param string $email_address Email address 
	 * @return string
	 */
	function uservalidationbyemail_generate_code($user_guid, $email_address)
	{
		global $CONFIG;
		
		return md5($user_guid . $email_address . $CONFIG->site->url); // Note I bind to site URL, this is important on multisite!
	}
	
	/**
	 * Request user validation email.
	 * Send email out to the address and request a confirmation.
	 *
	 * @param int $user_guid The user
	 * @return mixed
	 */
	function uservalidationbyemail_request_validation($user_guid)
	{
		global $CONFIG;
		
		$user_guid = (int)$user_guid;
		$user = get_entity($user_guid);

		if (($user) && ($user instanceof ElggUser))
		{
			// Work out validate link
			$link = $CONFIG->site->url . "action/email/confirm?u=$user_guid&c=" . uservalidationbyemail_generate_code($user_guid, $user->email);

			//Fabio: getting the messages from db if available (I know, ugly, should not fiddle around with this plugin...
	        	$confirm_subject_key = "email:validate:subject";
        		$confirm_body_key = "email:validate:body";

			$confirm_subject = get_custom_string_if_available($confirm_subject_key,elgg_echo($confirm_subject_key));
			$confirm_body = get_custom_string_if_available($confirm_body_key,elgg_echo($confirm_body_key));

			// Send validation email		
			$result = notify_user($user->guid, $CONFIG->site->guid, sprintf($confirm_subject, $user->username), sprintf($confirm_body, $user->name, $link), NULL, 'email');
			if ($result)
				system_message(elgg_echo('uservalidationbyemail:registerok'));

			forward($CONFIG->wwwroot . 'pg/pages/url/getting-started' );// Forward on success, assume everything else is an error...
					
				
			return $result;
		}
		
		return false;
	}
	
	/**
	 * Validate a user
	 *
	 * @param unknown_type $user_guid
	 * @param unknown_type $code
	 * @return unknown
	 */
	function uservalidationbyemail_validate_email($user_guid, $code)
	{
		$user = get_entity($user_guid);
		
		$valid = ($code == uservalidationbyemail_generate_code($user_guid, $user->email));
		if ($valid)
			set_user_validation_status($user_guid, true, 'email');
		
		return $valid;
	}

	/**
	 * Dreamfish customization: Check to see if there is an updated email text in the db
	 * If available, returns that string, otherwise returns original value
         *
	 * @param string $message_key
	 * @param string $message_value
	*/
	function get_custom_string_if_available($message_key, $message_value)
	{

                $entity_type = 'object';
		$subtype = 'df_custom_msg';
		$owner_guid = 0;

		$dbg = fopen ("/tmp/msg_debug.txt",'w');
		fwrite($dbg,"get_custom");
		$custom_messages = get_entities($entity_type, $subtype, $owner_guid);


		fwrite($dbg,"before loop");
		foreach ($custom_messages as $msg)
		{
			fwrite($dbg,"in loop");
		
        		if ($msg->title == $message_key)
        		{
				fwrite($dbg,"found");
		                return $msg->description;
        		}
		}		
				fwrite($dbg,"not found");
		fclose($dbg);
		return $message_value;
	} 
	
	// Initialise
	register_elgg_event_handler('init','system','uservalidationbyemail_init');

?>
