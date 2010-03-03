<?php
/**
 * Uncaptcha
 * 
 * @package Uncaptcha
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt
 * @copyright Brett Profitt 2008
 * @link http://eschoolconsultants.com
 * 
 * 
 * @todo
 * 	Document using this without options for reverse captcha only.
 * 	PHP checking for uservalidationbyemail enable / disabled to validation and enable.
 * 
 * Instead of hooking into the create user event, use the action override to check.
 * the register form will post to uncaptcha_confirm to check captcha.
 * uncaptcha_confirm will redirect to (my) register.php 
 * can redirect to uncaptcha_confirm which will redirect to the real 
 * This action will only be called when a user registeres via the form.
 * This will avoid any problems with register_user()
 * 
 * We'll still have to hook into the create user hook for non-self-registered users.
 * 
 */

/**
 * Initialise the plugin.
 *
 */
function uncaptcha_init() {
	global $CONFIG;
	
	//override the default register.php action
	register_action('register', true, $CONFIG->pluginspath . "uncaptcha/actions/register.php");
	
	// inserts form-mangling JS on the registration page.
	extend_view('account/forms/register', 'uncaptcha/register');
	
	// css to hide the trick field
	extend_view('css', 'uncaptcha/register_css');

	// allow us to catch validation events and enable / validate users immediately
	// as per config.
	register_elgg_event_handler('validate', 'user', 'uncaptcha_validate_user');
	
	register_plugin_hook('uncaptcha:register_user', 'user', 'uncaptcha_register_user');

	return true;
}

/*
 * Provides functions to enable and login user after registration.
 * 
 * @param $hook
 * @param $entity_type
 * @param $returnvalue
 * @param $params
 * @return unknown_type
 */
function uncaptcha_register_user($hook, $entity_type, $returnvalue, $params) {
	if (get_plugin_setting('instant_enable', 'uncaptcha')) {
		$params->enable();
	}
	
	if (get_plugin_setting('login_after', 'uncaptcha')) {
		if (login($params)) {
			system_message(elgg_echo('uncaptcha:register:auto_login'));
		}
	}
	
	return true;
}

/**
 * Validates the user through uncaptcha.
 * 
 * @param $event
 * @param $object_type
 * @param $object
 * @return unknown_type
 */
function uncaptcha_validate_user($event, $object_type, $object) {
	global $CONFIG;

	if (get_plugin_setting('instant_validate', 'uncaptcha')) {
		$validated = set_user_validation_status($object->guid, true, 'uncaptcha');
	}
}


/**
 * Generates a unique code based that is required to register.
 * 
 * @return str
 */
function uncaptcha_generate_code() {
	return md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . date('Y-m-d'));
}

register_elgg_event_handler('init','system','uncaptcha_init');