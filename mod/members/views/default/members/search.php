<?php

	/**
	 * Simple member search
	 **/
	 
?>

<div class="sidebarBox">
<!--
<h3><?php echo elgg_echo('members:searchtag'); ?></h3>

<form id="memberssearchform" action="<?php echo $vars['url']; ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Member tags" onclick="if (this.value=='Member tags') { this.value='' }" class="search_input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="user" />
	<input type="hidden" name="filter" value="search_tags" />	
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>
-->

<h3><?php echo elgg_echo('members:searchname'); ?></h3>
<form id="memberssearchform" action="<?php echo $vars['url']; ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Members name" onclick="if (this.value=='Members name') { this.value='' }" class="search_input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="user" />
	<input type="hidden" name="filter" value="search" />	
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>

<h3>Search by Skills</h3>
<?php 
	foreach(preg_split('/\,/', $CONFIG->member_skills) as $skill)
	{
		echo '<a href="' . $vars['url'] . 'mod/members/?filter=search_tags&tag=' . $skill . '">' . $skill . '</a><br>';
	}
?>
</div>