<?php

function ckeditor_init() {

	global $CONFIG;
	register_action('CKEditor/upload', false, $CONFIG->pluginspath . "CKEditor/actions/upload.php");
	register_plugin_hook('delete', 'user', 'Remove_files_when_user_isDel');
        extend_view('metatags','CKEditor/js/javascript');

}

function ckeditor_pagesetup()
{
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('ckeditor:admin_title'), $CONFIG->wwwroot . 'mod/CKEditor/admin.php');
	}
}

function Remove_files_when_user_isDel($hook, $entity_type, $returnvalue, $params)
{
	global $CONFIG;

	delete_orphaned_metastrings();
	$tables = get_db_tables();
	foreach ($tables as $table) {
		echo sprintf(elgg_echo('garbagecollector:optimize'), $table);
			
		if (optimize_table($table)!==false)
		echo elgg_echo('garbagecollector:ok');
		else
		echo elgg_echo('garbagecollector:error');

		echo "\n";
	}


}


register_elgg_event_handler('init','system','ckeditor_init');
register_elgg_event_handler('pagesetup','system','ckeditor_pagesetup');
?>
