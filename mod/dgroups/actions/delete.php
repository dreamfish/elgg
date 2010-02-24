<?php
	global $CONFIG;
		
	$guid = (int)get_input('dgroup_guid');
	$entity = get_entity($guid);
	
	if (($entity) && ($entity instanceof ElggGroup))
	{
		if ($entity->delete())
			system_message(elgg_echo('dgroup:deleted'));
		else
			register_error(elgg_echo('dgroup:notdeleted'));
	}
	else
		register_error(elgg_echo('dgroup:notdeleted'));
		
	$url_name = $_SESSION['user']->username;
	forward("{$vars['url']}pg/dgroups/member/{$url_name}");
?>