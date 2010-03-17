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

function validateForm() {
	var ok = true;
	var regform = document.forms.regform;


	if ( ! (regform.yes_dreamfish.checked  ) ) {
		alert("Please confirm that you read the terms and the guidelines!");
		ok = false;
	}

	if (regform.name.value == "") {
		alert("Please fill in your name");
		ok = false;
	}

	if (regform.email.value == "") {
		alert("Please provide an email address");
		ok = false;
	}

	if (regform.username.value == "") {
		alert("Please provide an username");
		ok = false;
	}

	if (regform.password.value == "") {
		alert("Password cannot be empty");
		ok = false;
	}

	if (regform.password2.value == "") {
		alert("Password repetition cannot be empty");
		ok = false;
	}

	if (regform.password.value != regform.password2.value) {
		alert("Passwords do not match!");
		ok = false;
	}

	if (ok) {
		regform.submit();
	} 
}
</script>
	
<?php
    error_log("Using DF register form.");
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
	$form_body .= elgg_view('input/button', array('type' => 'button', 'internalname' => 'validate',  'value' => elgg_echo('dreamfish_theme:join'), 'js' => 'onclick="validateForm()"'))  . " </p>";
?>

	
	<div id="register-box" style="width:930px;">
	<h2><?php echo elgg_echo('register'); ?></h2>
	<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/register", 'internalname' => 'regform', 'body' => $form_body)) ?>
	</div>
