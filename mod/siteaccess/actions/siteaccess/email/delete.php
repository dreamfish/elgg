<?php
    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");

    global $CONFIG;
    admin_gatekeeper();
    action_gatekeeper();
    $guid = get_input('guid');

    $email = get_entity($guid);
    if ($email) {
        if (delete_entity($email->guid))
            system_message(elgg_echo('siteaccess:email:delete:success'));
        else 
            register_error(elgg_echo('siteaccess:email:delete:fail'));
    }

    forward('pg/siteaccess/templates');
    exit;
?>
