<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	//$url = $object->getURL();
	$forumtopic = $object->guid;
	$dgroup_guid = $object->container_guid;
	
	$url = $vars['url'] . "mod/dgroups/topicposts.php?topic=" . $forumtopic . "&dgroup_guid=" . $dgroup_guid;
	
	$url_user = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("dgroupforum:river:postedtopic"),$url_user) . ": ";
	$string .= "<a href=\"" . $url . "\">" . $object->title . "</a>";
	
?>

<?php echo $string; ?>