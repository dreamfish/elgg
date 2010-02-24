<?php

    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

    global $CONFIG;

    // block non-admin users
    admin_gatekeeper();
    action_gatekeeper();

    // Get the user
    $guid = get_input('guid');
    $obj = get_entity($guid);

    if ( ($obj instanceof ElggUser) && ($obj->canEdit()))
    {
	set_user_validation_status($guid, true, 'admin');
	system_message(elgg_echo('siteaccess:admin:validate:success'));
        siteaccess_notify_user($obj, 'admin_activated');
        siteaccess_add_to_river($obj, 'admin');
    } else {
        register_error(elgg_echo('siteaccess:admin:validate:error'));
    }

    forward($_SERVER['HTTP_REFERER']);
    exit;
?>
