<?php
	/**
	 * User validation plugin.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	$english = array(
	
		/**
		 * Default translations
		 */
		
		'uservalidation:admin:confirm:fail' => "Account could not be activated !",
		'uservalidation:admin:confirm:success' => "Account has been activated !",
		'uservalidation:admin:registerok' => "Your account will be confirmed by the site-administrator. You will receive an email, when your account is activated. !", 
		'uservalidation:confirm:fail' => "Your account could not be activated ! Please try again or contact the site-administrator !",
		'uservalidation:confirm:success' => "Your account have succesfully activated !",
		'uservalidation:email:registerok' => "To activate your account, please confirm your email address by clicking on the link we sent you.", 
	
		/**
		 * eMail translations
		 */

		'uservalidation:adminmail:subject' => "New user-registration : %s !",
		'uservalidation:adminmail:body' => "
Hi Admin,
just now the new user %s (%s) has registered.
If you have 'validation by admin' activated, please make sure that you activated them as soon as possible !",
	
		'uservalidation:autodelete:subject' => "Some users have been auto-deleted !",
		'uservalidation:autodelete:body' => "
Hi Admin,
the following users have been auto-deleted :
%s",

		'uservalidation:admin:validate:subject' => "%s your account will be confirmed!",
		'uservalidation:admin:validate:body' => "
Hi %s,
your account will be confirmed by an adminstrator. After this, you'll receive an eMail to Login into 
%s",
	
		'uservalidation:email:validate:subject' => "%s please confirm your email address!",
		'uservalidation:email:validate:body' => "
Hi %s,
Please confirm your email address by clicking on the link below:
%s",
	
		'uservalidation:success:subject' => "Account activated %s!",
		'uservalidation:success:body' => "
Hi %s,
Congratulations, your account has been activated.
Login to %s with the link below:
%s",


	);
	add_translation('en', $english);
	
	
	if (isadminloggedin())
	{
	

		$english = array(

			/**
			* Admin-Only translations
			*/

			'uservalidation:activate' => "Activate user",
			'uservalidation:autodelete' => "Days, after that not activated users will be deleted",
			'uservalidation:autodelete:no' => "no auto-delete",
			'uservalidation:delete' => "Delete user", 
			'uservalidation:banned' => "Banned",
			'uservalidation:method' => "Method for UserValidation",
			'uservalidation:method:none' => "no validation",
			'uservalidation:method:bymail' => "validate by mail",
			'uservalidation:method:byadmin' => "validate by admin(s)",
			'uservalidation:pendingusers' => "Pending registration(s)",
			'uservalidation:registered' => "Registered: ",
			'uservalidation:adminmail' => "Admin receives eMail on registration",
			'uservalidation:adminmail:every' => "every registration",
			'uservalidation:adminmail:adminonly' => "only if an admin-action required",
			'uservalidation:waiting' => "Waiting for activation",

		);
		add_translation('en', $english);
	
	}
	
?>