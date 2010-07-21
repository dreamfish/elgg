reCAPTCHA .4(alpha)
Install instructions for Elgg
Module Overview
REMINDER:  Always make database backups and file level backups when modifying files and applying new modules.
This Elgg module is a rework of the original captcha module released by CurveRider.  
When installing the reCAPTCHA version, please disable and rename or remove the original captcha folder from your modules directory. (…/mods/captcha).  
You will need to modify the captcha.php and insert your own public and private keys accordingly. 
It is also recommended to replace the register.php form in order to avoid multiple submit/Register buttons.

Install Instructions
1.	Extract the reCAPTCHA module.
2.	Disable your existing captcha module.
4.	Remove or Rename existing captcha folder in the modules directory (…/mods/captcha)
5.	Upload the reCAPTCHA module to your mods directory.
6.      Set your API keys appropriately in the Tools Administration. - Save
6.	Enable and feel protected!

Register.php
If you are using Password Checker or any additions to your Registration form you will need to replace the existing Register.php.  
If you are not using the Password Checker module you will need to replace the existing Register.php in the accounts directory.

w/ Password Checker
1.	 Upload register.php to [mod/passwordchecker/views/default/account/forms]

w/o Password Checker
1.	Upload register.php to [views/default/account/forms]

Updates 2/8/10 
Creating a Settings option for the Administration Console. No need to hard set.