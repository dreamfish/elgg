<?php
 
    // pages on the group index page

    //check to make sure this group forum has been activated
    if($vars['entity']->pages_enable != 'no'){

?>

<div id="group_tasks_widget">
<h2><?php echo elgg_echo("tasks:group"); ?></h2>
<?php

	set_context('search');
    $objects = list_entities("object", "tasks", page_owner(), 5, false);
	set_context('tasks');
	$users_tasks_url = $vars['url'] . "pg/tasks/" . page_owner_entity()->username;

    if($objects){
		echo $objects;
		echo "<div class=\"forum_latest\"><a href=\"{$users_tasks_url}\">" . elgg_echo('tasks:more') . "</a></div>";
	}
	else
		echo "<div class=\"forum_latest\">" . elgg_echo("tasks:nogroup") . "</div>";
	
?>
<br class="clearfloat" />
</div>

<?php
    }
?>