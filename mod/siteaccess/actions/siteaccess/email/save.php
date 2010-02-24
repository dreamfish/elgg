<?php
    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");

    global $CONFIG;
    admin_gatekeeper();
    action_gatekeeper();
    $guid = get_input('guid');
    $subject = get_input('subject');
    $content = get_input('content');

    $email = get_entity($guid);
    if ($email) {
        $subject = sanitise_string($subject);
        $email->title = $subject;
        $email->description = $content;
        if ($email->save())
            system_message(elgg_echo('siteaccess:email:update:success'));
        else 
            register_error(elgg_echo('siteaccess:email:update:fail'));
    }

    forward('pg/siteaccess/templates');
    exit;
?>
