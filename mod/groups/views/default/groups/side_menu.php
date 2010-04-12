<div class="sidebarBox">
<div id="owner_block_submenu"><ul>
<?php
	if(isloggedin()){
		echo "<li><a href=\"{$vars['url']}pg/groups/member/{$_SESSION['user']->username}\">". elgg_echo('groups:yours') ."</a></li>";
		echo "<li><a href=\"{$vars['url']}pg/groups/new/\">". elgg_echo('groups:new') ."</a></li>";
	}
?>
</ul></div></div>
<div class="sidebarBox">
<h3>Search by Skills</h3>
<?php 
	foreach(preg_split('/\,/', $CONFIG->member_skills) as $skill)
	{
		echo '<a href="' . $vars['url'] . 'search/?tag=' . $skill . '&subtype=project&object=group&tagtype=">' . $skill . '</a><br>';
	}
?>
</div>