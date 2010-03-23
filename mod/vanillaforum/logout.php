<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

if (get_plugin_setting('logout_page', 'vanillaforum') != 'no') {

	// logout landing page with an invisible iframe logging the user out of Vanilla
	
	$title = elgg_echo('vanillaforum:logout:title');
	$iframe = elgg_view('vanillaforum/logout_iframe');
	
	$body = '<div class="contentWrapper">'.elgg_echo('vanillaforum:logout:description').'</div>'.$iframe;
	
	page_draw($title,elgg_view_layout("two_column_left_sidebar", '', elgg_view_title($title) . $body,elgg_view("account/forms/login")));
} else {
	// redirect to the front page and count on it to include the iframe itself
	forward($CONFIG->wwwroot.'?vanilla_logout=true');
}

?>