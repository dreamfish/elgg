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

?>
<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('dgroups:newest'); ?></a></li>
	<li <?php if($filter == "pop") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=pop"><?php echo elgg_echo('dgroups:popular'); ?></a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('dgroups:latestdiscussion'); ?></a></li>
</ul>
</div>
<div class="dgroup_count">
	<?php
		echo $num_dgroups . " " . elgg_echo("dgroups:count");
	?>
</div>