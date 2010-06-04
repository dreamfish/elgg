<?php

     /**
	 * Elgg register form
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>
<script type="text/javascript">

function validate_username(username)
{
	if (username == "") {
		alert("<?php echo elgg_echo("dreamfish_theme:provide_username"); ?>");
		return false;
	}
	
	var invalid_chars = new Array("/","\\","\"","'","*","&"," ");
	
	var i = 0;
	for (i=0; i<invalid_chars.length; i++)
	{
		if (is_char_in_string(invalid_chars[i],username))
		{
			var char = invalid_chars[i];
			if (char == ' ')
				char = "space";
			alert ("<?php echo elgg_echo("dreamfish_theme:invalid_char");?>" + char);
			return false;
		}
	}
	return true;
}

function strpos (haystack, needle, offset) {
    var i = (haystack+'').indexOf(needle, (offset ? offset : 0));
    return i === -1 ? false : i;
}

function is_char_in_string(char, string)
{
	if (strpos(string,char) !== false)
	{
		return true;
	}	
	else
	{
		return false;
	}
}


function validateForm() {
	var ok = true;
	var regform = document.forms.regform;


	if ( ! (regform.yes_dreamfish.checked  ) ) {
		alert("<?php echo elgg_echo("dreamfish_theme:read_terms");?>");
		ok = false;
	}
	
	if (regform.email.name == "") {
		alert("<?php echo elgg_echo("dreamfish_theme:no_name");?>");
		ok = false;
	}
	
	if (regform.email.value == "") {
		alert("<?php echo elgg_echo("dreamfish_theme:no_email");?>");
		ok = false;
	}

	ok = validate_username(regform.username.value);

	if (regform.password.value == "") {
		alert("<?php echo elgg_echo("dreamfish_theme:no_pwd");?>");
		ok = false;
	}

	if (regform.password2.value == "") {
		alert("<?php echo elgg_echo("dreamfish_theme:no_pwd2");?>");
		ok = false;
	}

	if (regform.password.value != regform.password2.value) {
		alert("<?php echo elgg_echo("dreamfish_theme:pwd_no_match");?>");
		ok = false;
	}

	if (ok) {
		regform.submit();
	} 
}
</script>
	
<?php
    //error_log("Using DF register form.");
	$username = get_input('u');
	$email = get_input('e');
	$name = get_input('n');

	$admin_option = false;

	if (($_SESSION['user']->admin) && ($vars['show_admin'])) 
		$admin_option = true;
		
	$form_body = "<p><label>" . elgg_echo('name') . "<br />" . elgg_view('input/text' , array('internalname' => 'name', 'class' => "general-textarea", 'value' => $name)) . "</label><br />";
	
	$form_body .= "<label>" . elgg_echo('email') . "<br />" . elgg_view('input/text' , array('internalname' => 'email', 'class' => "general-textarea", 'value' => $email)) . "</label><br />";
	$form_body .= "<label>" . elgg_echo('username') . "<br />" . elgg_view('input/text' , array('internalname' => 'username', 'class' => "general-textarea", 'value' => $username)) . "</label><br />";
	$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password' , array('internalname' => 'password', 'class' => "general-textarea")) . "</label><br />";
	$form_body .= "<label>" . elgg_echo('passwordagain') . "<br />" . elgg_view('input/password' , array('internalname' => 'password2', 'class' => "general-textarea")) . "</label><br />";
	
	$form_body .= "<label>" . elgg_echo('dreamfish_theme:newsletters') . "<br /></label>";
	$form_body .= "<input type=\"checkbox\" name=\"df_announce\" class=\"input-checkboxes\" checked=\"true\"/><label>" . elgg_echo('dreamfish_theme:df_announce') . "</label><br />";
	$form_body .= "<input type=\"checkbox\" name=\"df_new_projects\" class=\"input-checkboxes\" checked=\"true\"/><label>" . elgg_echo('dreamfish_theme:df_new_projects') . "</label><br />";
	
 	$form_body .= "<b><span style=\"font-size:.9em\">". elgg_echo('dreamfish_theme:accept_terms') . "</span></b><br>";

	//$form_body .= elgg_view('input/checkboxes', array('internalname' => "dreamfish_yes", 'options' => array(elgg_echo('yes_dreamfish')))) . "<br />";

	$form_body .= "<input type=\"checkbox\" name=\"yes_dreamfish\" class=\"input-checkboxes\" /><label>" . elgg_echo('yes_dreamfish') . "</label><br />";
	
	

	if ($admin_option)
		$form_body .= elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
	
	$form_body .= elgg_view('input/hidden', array('internalname' => 'friend_guid', 'value' => $vars['friend_guid']));
	$form_body .= elgg_view('input/hidden', array('internalname' => 'invitecode', 'value' => $vars['invitecode']));
	$form_body .= elgg_view('input/hidden', array('internalname' => 'action', 'value' => 'register'));
	$form_body .= '<img src="' . $vars['url'] . '/mod/dreamfish_theme/captcha.php" id="captcha" /><br/><a href="#" onclick="document.getElementById(\'captcha\').src=\'' . $vars['url'] . '/mod/dreamfish_theme/captcha.php?\'+Math.random();document.getElementById(\'captcha-form\').focus();" id="change-image">Not readable? Change text.</a><br/><br/>';
	$form_body .= '<input type="text" name="_captcha" id="captcha-form" /><br/>';
	$form_body .= '<input type="text" name="spam" id="spam" style="display:none"/><br/>';
	$form_body .= elgg_view('input/button', array('type' => 'button', 'internalname' => 'validate',  'value' => elgg_echo('dreamfish_theme:join'), 'js' => 'onclick="validateForm()"'))  . " </p>";
?>

	
	<div id="register-box" style="width:930px;">
	<h2><?php echo elgg_echo('register'); ?></h2>
	<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/register", 'internalname' => 'regform', 'body' => $form_body)) ?>
	</div>

