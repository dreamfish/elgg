<div class="sidebarBox">
<div id="owner_block_submenu"><ul>
<?php
	if(isloggedin()){
		echo "<li><a href=\"{$vars['url']}pg/dgroups/member/{$_SESSION['user']->username}\">". elgg_echo('dgroups:yours') ."</a></li>";
		if (isadminloggedin()) {
			echo "<li><a href=\"{$vars['url']}pg/dgroups/new/\">". elgg_echo('dgroups:new') ."</a></li>";
		}
	}
?>
</ul></div></div>