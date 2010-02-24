<?php

    /**
	 * Elgg dgroups plugin edit post action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

    // Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();
		
	// Check the user is a dgroup member
		$dgroup_guid = get_input('dgroup');
	    $dgroup_entity =  get_entity($dgroup_guid);
	    if (!$dgroup_entity->isMember($vars['user'])) forward();
	    
	//get the required variables
		$post = get_input("post");
		$field_num = get_input("field_num");
		$post_comment = get_input("postComment{$field_num}");
		$annotation = get_annotation($post);
		$commentOwner = $annotation->owner_guid;
		$access_id = $annotation->access_id;
		$topic = get_input("topic");
		
		if($annotation){
			
			//can edit? Either the comment owner or admin can
			if(dgroups_can_edit_discussion($annotation, page_owner_entity()->owner_guid)){
				
				update_annotation($post, "dgroup_topic_post", $post_comment, "",$commentOwner, $access_id);
			    system_message(elgg_echo("dgroups:forumpost:edited"));
				   
			}else{
				system_message(elgg_echo("dgroups:forumpost:error"));
			}
			
		}else{
			
				system_message(elgg_echo("dgroups:forumpost:error"));
		}
		
		// Forward to the dgroup forum page
	    global $CONFIG;
	    $url = $CONFIG->wwwroot . "mod/dgroups/topicposts.php?topic={$topic}&dgroup_guid={$dgroup_guid}/";
		forward($url);
  
		
?>