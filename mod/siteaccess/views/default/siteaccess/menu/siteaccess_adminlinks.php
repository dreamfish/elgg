<?php
    global $CONFIG;

    if (isadminloggedin()) {
	if($SESSION['id'] != $vars['entity']->guid){
	    $ts = time();
            $token = generate_action_token($ts);

	    $links = "";
	    if (!$vars['entity']->validated) {
		$links .= elgg_view('output/confirmlink', array('text' => elgg_echo('siteaccess:admin:links'), 'href' => "{$vars['url']}action/siteaccess/activate?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
	    }
            $links .= "<a href=\"". $CONFIG->wwwroot . "pg/siteaccess/invited/" . $vars['entity']->username ."\">" . elgg_echo('siteaccess:invitedusers') . "</a>";

	    echo $links;
	}
    }
?>
