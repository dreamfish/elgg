<?php
	/**
	 * Elgg dgroups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
?>

<div id="content_area_dgroup_title"><h2><?php echo elgg_echo("dgroups:forum"); ?></h2></div>

<?php
    //only show the add link if the user is a member
    if(page_owner_entity()->isMember($vars['user'])){
     
?>
        <!-- display the add a topic link -->
        <div class="add_topic"><a href="<?php echo $vars['url']; ?>mod/dgroups/addtopic.php?dgroup_guid=<?php echo get_input('dgroup_guid'); ?>" class="add_topic_button"><?php echo elgg_echo("dgroups:addtopic"); ?></a></div>

<?php
    }
?>    
<?php
	if($vars['topics'])
		echo $vars['topics'];
	else
		echo "<div class='contentWrapper'>". elgg_echo("dgrouptopic:notcreated") . "</div>";

?>