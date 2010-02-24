<?php
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
	$form_body .= elgg_view('siteaccess/register');
	if (siteaccess_key_enabled()) 
	    $form_body .= "<label>" . elgg_echo('siteaccess:key:enter') . "<br />" . elgg_view('input/password' , array('internalname' => 'siteaccesskey', 'value' => $siteaccesskey, 'class' => "general-textarea")) . "</label><br />";
	if (siteaccess_coppa_enabled())
	    $form_body .= "<label><br />" . elgg_view('input/checkboxes' , array('internalname' => 'coppa', 'options' => array(elgg_echo('siteaccess:coppa:text')), 'value' => 'coppa', 'class' => "general-textarea")) . "</label><br />";
	if ($admin_option)
	    $form_body .= elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
	
	if (extension_loaded("gd"))
	    $form_body .= elgg_view('siteaccess/code');
	$form_body .= elgg_view('input/hidden', array('internalname' => 'friend_guid', 'value' => $vars['friend_guid']));
        $form_body .= elgg_view('input/hidden', array('internalname' => 'invitecode', 'value' => $vars['invitecode']));
	$form_body .= elgg_view('input/hidden', array('internalname' => 'action', 'value' => 'register'));
	$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register'))) . "</p>";
?>

	
	<div id="register-box">
	<h2><?php echo elgg_echo('register'); ?></h2>
	<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/register", 'body' => $form_body)) ?>
	</div>
