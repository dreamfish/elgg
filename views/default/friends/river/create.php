<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$performed_on = get_entity($vars['item']->object_guid);
	$url = $performed_on->getURL();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("friends:river:add"),$url) . " ";
	$string .= "<a href=\"{$performed_on->getURL()}\">{$performed_on->name}</a>";

?>

<?php 
	echo $string; 
?>