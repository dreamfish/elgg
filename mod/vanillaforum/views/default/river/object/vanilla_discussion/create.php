<?php
	global $CONFIG;
	
	$performed_by = get_entity($vars['item']->subject_guid); 
	$object = get_entity($vars['item']->object_guid);
	$object_url = $CONFIG->wwwroot.'mod/vanillaforum/vanilla/comments.php?DiscussionID='.$object->did;
	
	$person_link = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$discussion_link = "<a href=\"{$object_url}\">{$object->title}</a>";
	$string = sprintf(elgg_echo("vanillaforum:river:discussion:created"),$person_link,$discussion_link,$object->description);

	echo $string;
?>