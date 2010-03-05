<?php
/**
 * Uncaptcha
 * 
 * @package Uncaptcha
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt
 * @copyright Brett Profitt 2008
 * @link http://eschoolconsultants.com
 */

/**
 * Default Settings
 */
if (!get_plugin_setting('trick_field_name', 'uncaptcha')) {
	set_plugin_setting('trick_field_name', 'email_address', 'uncaptcha');
}

$enable_selected = (get_plugin_setting('instant_enable', 'uncaptcha')) ? 'checked="checked"' : '';
$login_after_selected = (get_plugin_setting('login_after', 'uncaptcha')) ? 'checked="checked"' : '';
$trick_field_name = get_plugin_setting('trick_field_name', 'uncaptcha');

$validate_form = elgg_view('input/pulldown', array(
	'internalname' => 'params[instant_validate]',
	'value' => get_plugin_setting('instant_validate', 'uncaptcha'),
	'options_values' => array(
		1 => elgg_echo('option:yes'),
		0 => elgg_echo('option:no')
		)
	)
);

$login_after_form = elgg_view('input/pulldown', array(
	'internalname' => 'params[login_after]',
	'value' => get_plugin_setting('login_after', 'uncaptcha'),
	'options_values' => array(
		1 => elgg_echo('option:yes'),
		0 => elgg_echo('option:no')
		)
	)
);

$enable_form = elgg_view('input/pulldown', array(
	'internalname' => 'params[instant_enable]',
	'value' => get_plugin_setting('instant_enable', 'uncaptcha'),
	'options_values' => array(
		1 => elgg_echo('option:yes'),
		0 => elgg_echo('option:no')
		)
	)
);

$register_success_foward_form = elgg_view('input/pulldown', array(
	'internalname' => 'params[register_success_forward]',
	'value' => get_plugin_setting('register_success_forward', 'uncaptcha'),
	'options_values' => array(
		'' => elgg_echo('uncaptcha:settings:forward_dashboard'),
		'mod/profile/edit.php' => elgg_echo('uncaptcha:settings:forward_profile_edit'),
		'mod/profile/editicon.php' => elgg_echo('uncaptcha:settings:forward_profile_editicon'),
		'*user_profile*' => elgg_echo('uncaptcha:settings:forward_user_profile'),
		'pg/settings' => elgg_echo('uncaptcha:settings:forward_user_settings')
		)
	)
); 


// @todo.  Add some JS to makes the login_after only appear when both enable and validate are selected.

?>
<p>
<?php echo elgg_echo('uncaptcha:settings:blurb'); ?>
</p>
<p>
	<label><?php echo elgg_echo('uncaptcha:settings:trick_field_name'); ?> <input type="text" name="params[trick_field_name]" value="<?php echo $trick_field_name; ?>" /></label><br />
	<label><?php echo elgg_echo('uncaptcha:settings:instant_validate'); echo $validate_form; ?></label><br />
	<label><?php echo elgg_echo('uncaptcha:settings:instant_enable'); echo $enable_form; ?></label><br />
	<label><?php echo elgg_echo('uncaptcha:settings:login_after'); echo $login_after_form; ?></label><br />
	<label><?php echo elgg_echo('uncaptcha:settings:forward_to'); echo $register_success_foward_form; ?></label>
</p>