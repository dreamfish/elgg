<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();

set_context('admin');

set_page_owner($_SESSION['guid']);

$body = elgg_view("dreamfish_admin/forms/email_confirmation");

$title = elgg_echo('dreamfish_admin:admin_title');
$pagetitle = elgg_view_title($title);

$body = elgg_view_layout("two_column_left_sidebar", '', $pagetitle . $body);

page_draw($title,$body);


?>
