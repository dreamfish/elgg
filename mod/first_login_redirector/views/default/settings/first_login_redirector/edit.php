<?php

	global $CONFIG;
	
?>
	<br />
	<?php echo elgg_echo('first_login_redirector:admin:custom_redirect'); ?><br />
	<input type="text" name="params[custom_redirect]" 
          value="<?php echo $vars['entity']->custom_redirect; ?>"/>
        <br />

	<?php echo elgg_echo('first_login_redirector:admin:custom_redirect_info'); ?>
	<br />
	[wwwroot] = <?php echo $CONFIG->wwwroot; ?><br />
	[username] = <?php echo get_loggedin_user()->username;?>
</p>