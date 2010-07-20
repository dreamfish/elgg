<?php
	/**
	 * Elgg Recaptcha plugin input/captcha view override.
	 * 
	 * @package Recaptcha
	 * @license www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
	 * @author Emmanuel Okyere
	 * @copyright Emmanuel Okyere 2010
	 * @link http://www.hutspace.net/elgg/
	 */

	global $CONFIG;
	require_once($CONFIG->pluginspath . "recaptcha/lib/recaptchalib.php");

	$publickey = get_plugin_setting('publickey', 'recaptcha');	
?>
<div id="recaptcha_div">
	<?php echo recaptcha_get_html($publickey, $error); ?>
</div>