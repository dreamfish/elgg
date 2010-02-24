<?php

	$statement = $vars['statement'];
	$performed_by = $statement->getSubject();
	$object = $statement->getObject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("tasks:river:annotate"),$url) . " ";
	$string .= "<a href=\"" . $object->getURL() . "\">" . elgg_echo("tasks:river:item") . "</a>";

?>

<?php echo $string; ?>