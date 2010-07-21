<?php
	/**
	 * This is the form the admin uses to change recaptcha settings.
	 * 
	 * @package Recaptcha
	 * @license www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
	 * @author Emmanuel Okyere
	 * @copyright Emmanuel Okyere 2010
	 * @link http://www.hutspace.net/elgg/
	 */
?>
<?php
	$out = '<h2>' . elgg_echo('recaptcha:settings:heading') . '</h2>';
	$out .= '<p>' . elgg_echo('recaptcha:settings:description') . '</p>';
	$out .= '<label>';
	$out .= elgg_echo('recaptcha:settings:label:publickey');
	$out .= elgg_view('input/text', 
					  array('internalname'=>'params[publickey]', 
					  'value'=>get_plugin_setting('publickey', 'recaptcha')));
	$out .= '</label>';
	$out .= '<br />';
	$out .= '<label>';
	$out .= elgg_echo('recaptcha:settings:label:privatekey');
	$out .= elgg_view('input/text',
					  array('internalname'=>'params[privatekey]',
					  'value'=>get_plugin_setting('privatekey', 'recaptcha')));
	$out .= '</label>';
	
	echo $out;
?>