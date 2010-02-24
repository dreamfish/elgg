<?php

    /**
	 * Elgg dgroups plugin display topic posts
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

?>

<div id="topic_posts"><!-- open the topic_posts div -->
<div id="pages_breadcrumbs"><b><a href="<?php echo $vars['url']; ?>pg/dgroups/forum/<?php echo $vars['entity']->container_guid; ?>/"><?php echo elgg_echo("dgroups:forum"); ?></a></b> > <?php echo $vars['entity']->title; ?></div>
  
<?php
    //display follow up comments
    $count = $vars['entity']->countAnnotations('dgroup_topic_post');
    $offset = (int) get_input('offset',0);
    
    $baseurl = $vars['url'] . "mod/dgroups/topicposts.php?topic={$vars['entity']->guid}&dgroup_guid={$vars['entity']->container_guid}";
    echo elgg_view('navigation/pagination',array(
    												'limit' => 50,
    												'offset' => $offset,
    												'baseurl' => $baseurl,
    												'count' => $count,
    											));

?>
    <!-- grab the topic title -->
        <div id="content_area_dgroup_title"><h2><?php echo $vars['entity']->title; ?></h2></div>
<?php
    											
    foreach($vars['entity']->getAnnotations('dgroup_topic_post', 50, $offset, "asc") as $post) {
    		    
	     echo elgg_view("forum/topicposts",array('entity' => $post));
		
	}
	
	// check to find out the status of the topic and act
    if($vars['entity']->status != "closed" && page_owner_entity()->isMember($vars['user'])){
        
        //display the add comment form, this will appear after all the existing comments
	    echo elgg_view("forms/forums/addpost", array('entity' => $vars['entity']));
	    
    } elseif($vars['entity']->status == "closed") {
        
        //this topic has been closed by the owner
        echo "<h2>" . elgg_echo("dgroups:topicisclosed") . "</h2>";
        echo "<p>" . elgg_echo("dgroups:topiccloseddesc") . "</p>";
        
    } else {
    }

?>
</div>