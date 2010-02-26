<?php

	/**
	 * A simple view to provide the user with group filters and the number of group on the site
	 **/
	 
	 $num_groups = $vars['count'];
	 if(!$num_groups)
	 	$num_groups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = $vars['url'] . "pg/groups/world/";

	$pages = search_for_object('DF_'.'Starting_A_Group');
	if ($pages && sizeof($pages) > 0) {
		$toptext .= $pages[0]->description;
	} 

?>
<?php echo $toptext ?>
<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('groups:newest'); ?></a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('groups:latestdiscussion'); ?></a></li>
</ul>
</div>
<div class="group_count">
	<?php
		echo $num_groups . " " . elgg_echo("groups:count");
	?>
</div>