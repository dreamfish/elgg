<?php

	$english = array(
		'siteaccess:usesiteaccesskey' => 'Require site Password to register?',
                'siteaccess:usesiteaccessemail' => 'Allow account activation via email?',
                'siteaccess:usesiteaccesscoppa' => 'Require coppa to register?',
		'siteaccess:invitecode' => 'Require invitation to register?',
		'siteaccess:key:enter' => 'Enter Site Password',
		'siteaccess:key:invalid' => 'Invalid Site Password!',
		'siteaccess:admin:links' => 'Activate',
		'siteaccess:admin:menu' => 'Site Access',
		'siteaccess:admin:validate:success' => 'User was successfully activated!',
		'siteaccess:admin:validate:error' => 'Failed to activate user',
                'siteaccess:list:templates' => 'Email Templates',
		'siteaccess:list:header' => 'Select which users to view:',
		'siteaccess:list:activate' => 'Users waiting to be activated',
		'siteaccess:list:validate' => 'Emails not validated',
		'siteaccess:list:banned' => 'Banned users',
                'siteaccess:email:default' => 'Revert to Default',
                'siteaccess:email:valid:macros' => 'Possible Email Macros',
                'siteaccess:email:delete:success' => 'Email deleted successfully!',
                'siteaccess:email:delete:fail' => 'Failed to delete email!',
                'siteaccess:email:update:success' => 'Email updated successfully!',
                'siteaccess:email:update:fail' => 'Failed to update email!', 
                'siteaccess:email:label:subject' => 'Subject:',
                'siteaccess:email:label:content' => 'Content:',
                'siteaccess:email:label:adminactivated' => 'Admin Activated Email', 
                'siteaccess:email:label:confirmed' => 'Email Confirmation',
                'siteaccess:email:label:validated' => 'Email Validation',
                'siteaccess:email:label:notifyadmin' => 'Admin Notification Email', 

		'siteaccess:email:adminactivated:subject' => '[%site_name%] Admin activated %username%!',
                'siteaccess:email:adminactivated:content' => 'Hi %name%,

Congratulations, your account has been activated by the Administrator. You can now login to the site.

%site_url%',
		'siteaccess:email:confirm:subject' => "[%site_name%] %username% please confirm your email address!",
                'siteaccess:email:confirm:content' => "Hi %name%,

Please confirm your email address by clicking on the link below:

%confirm_url%",
                'siteaccess:email:validated:subject' => "[%site_name%] Email validated %username%!",
                'siteaccess:email:validated:content' => "Hi %name%,

Congratulations, you have successfully validated your email address. 

%site_url%",
		'siteaccess:confirm:success' => "You have confirmed your email address!",
                'siteaccess:confirm:fail' => "Your email address could not be verified...",
		'siteaccess:authorize' => 'This site requires that the Administrator authorizes your account!',
                'siteaccess:confirm:email' => "Please confirm your email address by clicking on the link we just sent you.",
		'siteaccess:email:validated' => 'Validated',
		'siteaccess:email:notvalidated' => 'Not Validated',
		'siteaccess:coppa:text' => 'I am at least 13 years of age',
		'siteaccess:coppa:fail' => 'You must be at least 13 years of age to register for this website',
		'siteaccess:code:invalid' => 'Invalid security code entered!',
		'siteaccess:email:notifyadmin:subject' => '[%site_name%] You have users in your activation queue',
		'siteaccess:email:notifyadmin:content' => 'Hi %name%,

You have users in your queue waiting to be activated or still needing email validation

%admin_url%',
		'siteaccess:notify' => 'Enter username to notify of new users in the activation queue?',
		'siteaccess:hourly' => 'hourly',
		'siteaccess:daily' => 'daily',
		'siteaccess:weekly' => 'weekly',
		'siteaccess:monthly' => 'monthly',
		'siteaccess:invitecode:invalid' => 'Registration by invitation only',
		'siteaccess:invitecode:info' => 'Requires plugin "invitefriends"',
		'siteaccess:walledgarden' => 'Enable walledgarden?',
		'siteaccess:walledgarden:allow' => 'You must be logged in to view this page!',
		'siteaccess:invited' => 'This user was invited by',
		'siteaccess:invitedbyuser' => 'Users invited by',
		'siteaccess:invitedusers' => 'View Invited Users',
                'siteaccess:river:join' => '%s has joined the network',
		'siteaccess:river:activate' => '%s has activated their account',
                'siteaccess:river:admin' => 'Account has been activated %s',
		'siteaccess:useriver' => 'Send join events to River Dashboard?',
		'siteaccess:walledgarden:debug' => 'Enable Walledgarden Debug Mode?',
		'siteaccess:walledgarden:options' => 'Walledgarden Options',
		'siteaccess:notify:options' => 'Notification Options',
                'siteaccess:found' => 'Users found',
		'siteaccess:reg:options' => 'Registration Options',
		'siteaccess:autoactivate' => 'Auto activate account? (does not enforce email validation)',
		'siteaccess:accesslist' => 'Access Control list of approved pages for walledgarden, one page per line'
	);
					
	add_translation("en",$english);
?>
