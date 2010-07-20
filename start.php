<?php
	/**
	 * Elgg Recaptcha plugin
	 * 
	 * @package Recaptcha
	 * @license www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
	 * @author Emmanuel Okyere
	 * @copyright Emmanuel Okyere 2010
	 * @link http://www.hutspace.net/elgg/
	 */

	function recaptcha_init() {
		register_plugin_hook('action', 'register', 'recaptcha_verify_action_hook');
	}
	
	/**
	 * Listen to the action plugin hook and check the recaptcha.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function recaptcha_verify_action_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		include($CONFIG->pluginspath . "recaptcha/lib/recaptchalib.php");

		$privatekey = "6LdQTrsSAAAAAIF7itBW36LSuM4wf7iivZlEJec_";
		$resp = recaptcha_check_answer($privatekey,
									   $_SERVER["REMOTE_ADDR"],
									   $_POST["recaptcha_challenge_field"],
									   $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			register_error(elgg_echo('captcha:captchafail'));
			return false;
		}

		return true;
	}
	
	register_elgg_event_handler('init','system','recaptcha_init');
?>