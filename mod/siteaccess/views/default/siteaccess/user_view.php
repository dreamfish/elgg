<?php
    	$icon = elgg_view("profile/icon", array(
		'entity' => $vars['entity'],
		'size' => 'small',
		));
		
	$banned = $vars['entity']->isBanned();
	$ts = $vars['ts'];
	$token = $vars['token'];

	// Simple XFN
	$rel = "";
	if (page_owner() == $vars['entity']->guid)
		$rel = 'me';
	else if (check_entity_relationship(page_owner(), 'friend', $vars['entity']->guid))
		$rel = 'friend';
	
	$email_validated = $vars['entity']->validated_email;
	
	if (!$banned) {
		$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></b>";
                $info .= " (" . $vars['entity']->email  . ") ";
	}
	else
	{
		$info .= "<p><b><strike>";
		if (isadminloggedin())
			$info .= "<a href=\"" . $vars['entity']->getUrl() . "\">";
		$info .= $vars['entity']->name;
		if (isadminloggedin())
			$info .= "</a>";
		$info .= "</strike></b> (" . $vars['entity']->email  . ") ";
	}
	$info .= "<b>Email:</b> ";
	if ($email_validated) {
            $info .= "<span class=\"siteaccess_validated\">" . elgg_echo('siteaccess:email:validated') . "</span>";
        } else {
	    $info .= "<span class=\"siteaccess_notvalidated\">" . elgg_echo('siteaccess:email:notvalidated') . "</span>";
        }
	$info .= "</p>";

	if ($vars['entity']->invited_by_guid) {
	    $friend_user = get_entity($vars['entity']->invited_by_guid);
	    if ($friend_user->username != get_input('friend_username'))
		$info .= "<p>" . elgg_echo('siteaccess:invited') . " <b><a href=\"" . $friend_user->getUrl() . "\" rel=\"$rel\">" . $friend_user->name . "</a></b></p>";
	}
	if ($vars['show'] == "banned") {
	    $info .= "<p class=\"siteaccess_entity\"><b>Last Login: </b>" . date("F j, Y, g:i a", $vars['entity']->last_login) . "</p>";
	} else {
	    $info .= "<p class=\"siteaccess_entity\"><b>Created: </b>" . date("F j, Y, g:i a", $vars['entity']->getTimeCreated()) . "</p>";
	}
	$info .= "<div class=\"siteaccess_links\">";
	if ($vars['show'] == "banned") {
	    $info .= elgg_view('output/confirmlink', array('text' => elgg_echo("unban"), 'href' => "{$vars['url']}action/admin/user/unban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));	    
        } else {
            if (!$vars['entity']->validated)
	        $info .= elgg_view('output/confirmlink', array('text' => elgg_echo('siteaccess:admin:links'), 'href' => "{$vars['url']}action/siteaccess/activate?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
        }
	$info .= elgg_view('output/confirmlink', array('text' => elgg_echo("delete"), 'href' => "{$vars['url']}action/admin/user/delete?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</div>";
	
        echo elgg_view_listing($icon, $info);
?>
