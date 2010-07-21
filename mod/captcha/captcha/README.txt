reCAPTCHA .3(alpha)
Install instructions for Elgg
Module Overview
REMINDER:  Always make database backups and file level backups when modifying files and applying new modules.
This Elgg module is a rework of the original captcha module released by CurveRider.  
When installing the reCAPTCHA version, please disable and rename or remove the original captcha folder from your modules directory. (…/mods/captcha).  
You will need to modify the captcha.php and insert your own public and private keys accordingly. 
It is also recommended to replace the register.php form in order to avoid multiple submit/Register buttons.

Install Instructions
1.	Extract the reCAPTCHA module.
2.	Open the TWO captcha.php files and insert your public and private keys and save.

a.	[Captcha/captcha.php]
Find the line shown below:
// Get a key from http://recaptcha.net/api/getkey
$publickey = "Insert your Key here";
$privatekey = "Insert your Key here";

b.	[Captcha/views/default/input/captcha.php]
Find the line shown below:
    // Get a key from http://recaptcha.net/api/getkey
    $publickey = "Insert your Key here";
    $privatekey = "Insert your Key here";

3.	Disable your existing captcha module.
4.	Remove or Rename existing captcha folder in the modules directory (…/mods/captcha)
5.	Upload the reCAPTCHA module to your mods directory.
6.	Enable and feel protected!

Register.php
If you are using Password Checker or any additions to your Registration form you will need to replace the existing Register.php.  
If you are not using the Password Checker module you will need to replace the existing Register.php in the accounts directory.

w/ Password Checker
1.	 Upload register.php to [mod/passwordchecker/views/default/account/forms]

w/o Password Checker
1.	Upload register.php to [views/default/account/forms]

