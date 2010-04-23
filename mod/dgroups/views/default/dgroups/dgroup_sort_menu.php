<?php

	/**
	 * A simple view to provide the user with dgroup filters and the number of dgroup on the site
	 **/
	 
	 $num_dgroups = $vars['count'];
	 if(!$num_dgroups)
	 	$num_dgroups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = $vars['url'] . "pg/dgroups/world/";

	$pages = search_for_object('DF_'.'Starting_A_Group');
	if ($pages && sizeof($pages) > 0) {
		$toptext .= $pages[0]->description;
	} 


?>
<?php echo $toptext ?>
<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('dgroups:newest'); ?></a></li>
</ul>
</div>
<div class="dgroup_count">
	<?php
		echo $num_dgroups . " " . elgg_echo("dgroups:count");
	?>
</div>