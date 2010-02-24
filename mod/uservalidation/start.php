<?php
	/**
	 * User validation plugin.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */
	 
	function uservalidation_init()
	{

		global $CONFIG;

		// Get the validation-method
		$validationMethod = get_plugin_setting('validationMethod', 'uservalidation');
		if (empty($validationMethod))
			$validationMethod = 'bymail';
		// Get the sendAdminMail option
		$sendAdminMail = get_plugin_setting('sendAdminMail', 'uservalidation');
		if (empty($sendAdminMail))
			$sendAdminMail = 'no';
		$autoDeleteDays = intval(get_plugin_setting('autoDeleteDays', 'uservalidation'));
		
		// Save the options to CONFIG
		$CONFIG->uservalidation->sendAdminMail = $sendAdminMail;
		
		if ($autoDeleteDays > 0) 
		{
			// Make a Query to get all disabled users to delete
			$deleteTime = (time() - ($autoDeleteDays * 24 * 60 * 60));
			$result = get_data("SELECT guid FROM {$CONFIG->dbprefix}entities WHERE type = 'user' AND enabled = 'no' AND time_created < {$deleteTime}");
			if (count($result)) 
			{
				$access_status = access_get_show_hidden_status();
				access_show_hidden_entities(true);
				foreach ($result AS $result_guid) 
				{
					$user = get_entity(intval($result_guid->guid));
					if (($user) && ($user instanceof ElggUser))
					{
						if (empty($user->prev_last_action))
						{
							$message .= "{$user->name} ($user->username)\n"; 
							$user->delete();
							
						}
					}
				}
				access_show_hidden_entities($access_status);
				if (!empty($message)) 
				{
					@notify_user(2, $CONFIG->site->guid, elgg_echo('uservalidation:autodelete:subject', $CONFIG->site->language), sprintf(elgg_echo('uservalidation:autodelete:body', $CONFIG->site->language), $message), NULL, 'email');
				}
			}
		}
		
		// Default action to confirm the registration
		register_action('uservalidation/confirmuser', true, $CONFIG->pluginspath . 'uservalidation/actions/confirmuser.php');
		// Register event-handler depending on the validation-method
		register_elgg_event_handler('user', 'validate', 'uservalidation_' . $validationMethod . '_validation');
		
		// Do this stuff only if an admin logged in
		if (isadminloggedin())
		{
			if ($validationMethod != 'none')
			{
				register_page_handler('uservalidation','uservalidation_page_handler');
				add_submenu_item(elgg_echo('uservalidation:pendingusers'), $CONFIG->wwwroot . 'pg/uservalidation/', 10000);
				register_action('uservalidation/deleteuser', true, $CONFIG->pluginspath . 'uservalidation/actions/deleteuser.php');
				register_action('uservalidation/activateuser', true, $CONFIG->pluginspath . 'uservalidation/actions/activateuser.php');
			}
		}

	}

	
	/**
	 * Uservalidation page.
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function uservalidation_page_handler($page) 
	{
		
		global $CONFIG;
		include($CONFIG->pluginspath . 'uservalidation/index.php'); 
		
	}

	
	/**
	 * Request no validation.
	 */
	function uservalidation_none_validation($event, $object_type, $object)
	{

		global $CONFIG;
		if (($object) && ($object instanceof ElggUser))
		{
			if ($CONFIG->uservalidation->sendAdminMail == 'every')
			{
				@notify_user(2, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:adminmail:subject', $CONFIG->site->language), $object->name), sprintf(elgg_echo('uservalidation:adminmail:body', $CONFIG->site->language), $object->name, $object->username), NULL, 'email');
			}
			forward($CONFIG->site->url . 'action/uservalidation/confirmuser?m=none&u=' . $object->guid . '&c=' . uservalidation_generate_code($object->guid, $object->email));
		}
		
	}
	
	/**
	 * Request admin validation.
	 */
	function uservalidation_byadmin_validation($event, $object_type, $object)
	{
	
		global $CONFIG;
		if (($object) && ($object instanceof ElggUser))
		{
			$result = notify_user($object->guid, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:admin:validate:subject'), $object->name), sprintf(elgg_echo('uservalidation:admin:validate:body'), $object->name, $CONFIG->site->name), NULL, 'email');
			if ($result)
				if ($CONFIG->uservalidation->sendAdminMail == 'every' || $CONFIG->uservalidation->sendAdminMail == 'adminonly')
				{
					@notify_user(2, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:adminmail:subject', $CONFIG->site->language), $object->name), sprintf(elgg_echo('uservalidation:adminmail:body', $CONFIG->site->language), $object->name, $object->username), NULL, 'email');
				}
				system_message(elgg_echo('uservalidation:admin:registerok'));
		}
		
	}

	/**
	 * Request email validation.
	 */
	function uservalidation_bymail_validation($event, $object_type, $object)
	{

		global $CONFIG;
		if (($object) && ($object instanceof ElggUser))
		{
			$link = $CONFIG->site->url . 'action/uservalidation/confirmuser?m=email&u=' . $object->guid . '&c=' . uservalidation_generate_code($object->guid, $object->email);
			$result = notify_user($object->guid, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:email:validate:subject'), $object->name), sprintf(elgg_echo('uservalidation:email:validate:body'), $object->name, $link), NULL, 'email');
			if ($result)
				if ($CONFIG->uservalidation->sendAdminMail == 'every')
				{
					@notify_user(2, $CONFIG->site->guid, sprintf(elgg_echo('uservalidation:adminmail:subject', $CONFIG->site->language), $object->name), sprintf(elgg_echo('uservalidation:adminmail:body', $CONFIG->site->language), $object->name, $object->username), NULL, 'email');
				}
				system_message(elgg_echo('uservalidation:email:registerok'));
		}
		
	}
	

	/**
	 * Generate an email activation code.
	 *
	 * @param int $user_guid The guid of the user
	 * @param string $email_address Email address of the user
	 * @return string Activationcode
	 */
	function uservalidation_generate_code($user_guid, $email_address)
	{
		
		global $CONFIG;
		return md5($user_guid . $email_address . $CONFIG->site->url);
			
	}
	
	/**
	 * Validate a user
	 *
	 * @param array  $user The User array
	 * @param string $code
	 * @return unknown
	 */
	function uservalidation_validate_code($user, $code)
	{
		
		$valid = ($code == uservalidation_generate_code($user->guid, $user->email));
		return $valid;
		
	}
	
	// Init
	register_elgg_event_handler('init','system','uservalidation_init');
	
?>