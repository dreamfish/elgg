<?php

function dreamfish_admin_init() {

	global $CONFIG;
	register_action('dreamfish_admin/email_confirm', false, $CONFIG->pluginspath . "dreamfish_admin/actions/email_confirm.php");
	register_action('dreamfish_admin/welcome', false, $CONFIG->pluginspath . "dreamfish_admin/actions/welcome.php");
        //extend_view('metatags','dreamfish_admin/js/javascript');

}

function dreamfish_admin_pagesetup()
{
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('dreamfish_admin:admin_title'), $CONFIG->wwwroot . 'mod/dreamfish_admin/admintasks.php');
	}
}



register_elgg_event_handler('init','system','dreamfish_admin_init');
register_elgg_event_handler('pagesetup','system','dreamfish_admin_pagesetup');
?>
