<?php
error_log(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

error_log("###########################################################");
//set_page_owner($_SESSION['guid']);

$iframe = "vanillaforum/df_iframe";
$body = elgg_view('vanillaforum/df_iframe');
error_log($iframe);
$dreamfish = "Dreamfish";
$title = sprintf(elgg_echo('vanillaforum:forum_description'),$dreamfish);
$pagetitle = elgg_view_title($title);

$body = elgg_view_layout("two_column_left_sidebar", '', $pagetitle . $body);

page_draw($title,$body);


?>
