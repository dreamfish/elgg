<?php

	/**
	 * Elgg dgroups: delete topic comment action
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Ensure we're logged in
		if (!isloggedin()) forward();
		
  
	// Make sure we can get the comment in question
		$post_id = (int) get_input('post');
		$dgroup_guid = (int) get_input('dgroup');
		$topic_guid = (int) get_input('topic');
		
		if ($post = get_annotation($post_id)) {
			
			//check that the user can edit as well as admin
			if ($post->canEdit() || ($post->owner_guid == $_SESSION['user']->guid)) {
    			
    			//delete
				$post->delete();
				//display confirmation message
				system_message(elgg_echo("dgrouppost:deleted"));
				
			}
			
		} else {
			$url = "";
			system_message(elgg_echo("dgrouppost:notdeleted"));
		}
		
    // Forward to the dgroup forum page
    global $CONFIG;
	$url = $CONFIG->wwwroot . "mod/dgroups/topicposts.php?topic={$topic_guid}&dgroup_guid={$dgroup_guid}";
	forward($url);

?>