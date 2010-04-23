<?php

	/**
	 * This view will display featured dgroups - these are set by admin
	 **/
	 
	
?>
<div class="sidebarBox featureddgroups">
<h3><?php echo elgg_echo("dgroups:featured"); ?></h3>

<?php
	if($vars['featured']){
		
		foreach($vars['featured'] as $dgroup){
			$icon = elgg_view(
				"dgroups/icon", array(
									'entity' => $dgroup,
									'size' => 'small',
								  )
				);
				
			echo "<div class=\"contentWrapper\">" . $icon . " <p><span><a href='" . $dgroup->getURL() . "'>" . $dgroup->name . "</a></span><br />";
			echo $dgroup->briefdescription . "</p><div class=\"clearfloat\"></div></div>";
			
		}
	}
?>
</div>