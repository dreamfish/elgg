<?php

	/**
	 * A simple view to provide the user with group filters and the number of group on the site
	 **/
	 
	 $members = $vars['count'];
	 if(!$num_groups)
	 	$num_groups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = $vars['url'] . "mod/members/index.php";

	$pages = search_for_object('DF_'.'PeopleListContent');
	if ($pages && sizeof($pages) > 0) {
		$toptext .= $pages[0]->description;
	} 


?>
<?php echo $toptext ?>
<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest">Newest</a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active">Active</a></li>
</ul>
</div>

<div class="group_count">
	<?php
		echo elgg_echo("members:active");
	?>
</div>