<?php

$blurb = 'The trick field name is the name of a field that will be added to the registration.  It must be a unique unused form name.  The default "email_address" will work for standard installations.';

$english = array(
	'uncaptcha:register:auto_login' => 'You have automatically been logged in.',
	'uncaptcha:register:non_empty_field' => 'Registration failed!  Please make sure Javascript is enabled!',
	'uncaptcha:register:bad_code' => 'Unable to verify registration code.  Please make sure Javascript is enabled!',
	'uncaptcha:settings:trick_field_name' => 'Trick field name:',
	'uncaptcha:settings:instant_validate' => 'Validate users immediately after registration?',
	'uncaptcha:settings:instant_enable' => 'Enable users immediately after registration?',
	'uncaptcha:settings:login_after' => 'Log in users immediately after registration?',
	'uncaptcha:settings:blurb' => $blurb,
	'uncaptcha:settings:forward_to' => 'After registration, forward to this page.',

	'uncaptcha:settings:forward_profile_edit' => 'Edit Profile Details',
	'uncaptcha:settings:forward_profile_editicon' => 'Edit Profile Icon',
	'uncaptcha:settings:forward_dashboard' => 'Dashboard (Front Page)',
	'uncaptcha:settings:forward_user_profile' => 'User Profile',
	'uncaptcha:settings:forward_user_settings' => 'User Settings',
	

);

add_translation("en", $english);
?>
