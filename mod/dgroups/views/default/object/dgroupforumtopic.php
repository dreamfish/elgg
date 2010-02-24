<?php

	/**
	 * Elgg dgroups latest discussion listing
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
    //get the required variables
    $title = $vars['entity']->title;
    //$description = get_entity($vars['entity']->description);
    $topic_owner = get_user($vars['entity']->owner_guid);
    $dgroup = get_entity($vars['entity']->container_guid);
    $forum_created = friendly_time($vars['entity']->time_created);
    $counter = $vars['entity']->countAnnotations("dgroup_topic_post");
	$last_post = $vars['entity']->getAnnotations("dgroup_topic_post", 1, 0, "desc");
 
    //get the time and user
    if ($last_post) {
		foreach($last_post as $last)
		{
			$last_time = $last->time_created;
			$last_user = $last->owner_guid;
		}
	}

	$u = get_user($last_user);
	
	//select the correct output depending on where you are
	if(get_context() == "search"){
	
	    $info = "<p class=\"latest_discussion_info\">" . sprintf(elgg_echo('dgroup:created'), $forum_created, $counter) .  "<br /><span class=\"timestamp\">";
	    if ($last_time) $info.= sprintf(elgg_echo('dgroups:lastupdated'), friendly_time($last_time), " <a href=\"" . $u->getURL() . "\">" . $u->name . "</a>");
	    $info .= '</span></p>';
		//get the dgroup avatar
		$icon = elgg_view("profile/icon",array('entity' => $dgroup, 'size' => 'small'));
	    //get the dgroup and topic title
	    if ($dgroup instanceof ElggGroup)
	    	$info .= "<p>" . elgg_echo('dgroup') . ": <a href=\"{$dgroup->getURL()}\">{$dgroup->name}</a></p>";
	    
		$info .= "<p>" . elgg_echo('topic') . ": <a href=\"{$vars['url']}mod/dgroups/topicposts.php?topic={$vars['entity']->guid}&dgroup_guid={$dgroup->guid}\">{$title}</a></p>";
		//get the forum description
		//$info .= $description;
		
	}else{
		
		$info = "<span class=\"latest_discussion_info\"><span class=\"timestamp\">" . sprintf(elgg_echo('dgroup:created'), $forum_created, $counter) . "</span>";
		if ($last_time) $info.= "<br /><span class='timestamp'>" . elgg_echo('dgroups:updated') . " " . friendly_time($last_time) . " by <a href=\"" . $u->getURL() . "\">" . $u->name . "</a></span>";

		    if (dgroups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {
	
	                	// display the delete link to those allowed to delete
	                	$info .= "<br /><span class=\"delete_discussion\">" . elgg_view("output/confirmlink", array(
	                																'href' => $vars['url'] . "action/dgroups/deletetopic?topic=" . $vars['entity']->guid . "&dgroup=" . $vars['entity']->container_guid,
	                																'text' => " ",
	                																'confirm' => elgg_echo('deleteconfirm'),
	                															)) . "</span>";
	                				
	           }		
		
		$info .= "</span>";
		
	    //get the user avatar
		$icon = elgg_view("profile/icon",array('entity' => $topic_owner, 'size' => 'small'));
	    $info .= "<p>" . elgg_echo('dgroups:started') . " " . $topic_owner->name . ": <a href=\"{$vars['url']}mod/dgroups/topicposts.php?topic={$vars['entity']->guid}&dgroup_guid={$dgroup->guid}\">{$title}</a></p>";
		$info .= "<div class='clearfloat'></div>";
		
	}
		
		//display
		echo elgg_view_listing($icon, $info);
		
?>