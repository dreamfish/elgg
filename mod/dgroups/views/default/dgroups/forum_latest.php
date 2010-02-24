<?php
 
    // Latest forum discussion for the dgroup home page

    //check to make sure this dgroup forum has been activated
    if($vars['entity']->forum_enable != 'no'){

?>

<div class="contentWrapper">
<h2><?php echo elgg_echo('dgroups:latestdiscussion'); ?></h2>
<?php
	
    $forum = get_entities_from_annotations("object", "dgroupforumtopic", "dgroup_topic_post", "", 0, $vars['entity']->guid, 4, 0, "desc", false);
	
    if($forum){
        foreach($forum as $f){
        	    
                $count_annotations = $f->countAnnotations("dgroup_topic_post");
                 
        	    echo "<div class=\"forum_latest\">";
        	    echo "<div class=\"topic_owner_icon\">" . elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'tiny', 'override' => true)) . "</div>";
    	        echo "<div class=\"topic_title\"><p><a href=\"{$vars['url']}mod/dgroups/topicposts.php?topic={$f->guid}&dgroup_guid={$vars['entity']->guid}\">" . $f->title . "</a></p> <p class=\"topic_replies\"><small>".elgg_echo('dgroups:posts').": " . $count_annotations . "</small></p></div>";
    	        	
    	        echo "</div>";
    	        
        }
    } else {
		echo "<div class=\"forum_latest\">";
		echo elgg_echo("dgrouptopic:notcreated");
		echo "</div>";
    }
?>
<div class="clearfloat" /></div>
</div>
<?php
	}//end of forum active check
?>