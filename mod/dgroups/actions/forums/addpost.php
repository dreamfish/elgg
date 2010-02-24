<?php

	/**
	 * Elgg dgroups: add topic post action
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	 
	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Check the user is a dgroup member
	    $dgroup_entity =  get_entity(get_input('dgroup_guid'));
	    if (!$dgroup_entity->isMember($vars['user'])) forward();
		
	// Get input
		$topic_guid = (int) get_input('topic_guid');
		$dgroup_guid = (int) get_input('dgroup_guid');
		$post = get_input('topic_post');
		
	// Let's see if we can get an entity with the specified GUID, and that it's a dgroup forum topic
		if ($topic = get_entity($topic_guid)) {
			if ($topic->getSubtype() == "dgroupforumtopic") {
    			
    			//check the user posted a message
    		    if($post){
	                // If posting the comment was successful, say so
				    if ($topic->annotate('dgroup_topic_post',$post,$topic->access_id, $_SESSION['guid'])) {
					
					    system_message(elgg_echo("dgroupspost:success"));
						// add to river
	        			add_to_river('river/forum/create','create',$_SESSION['user']->guid,$topic_guid);
	
				    } else {
					    system_message(elgg_echo("dgroupspost:failure"));
				    }
			    }else{
    			    system_message(elgg_echo("dgroupspost:nopost"));
			    }
			
			}
				
		} else {
		
			system_message(elgg_echo("dgroupstopic:notfound"));
			
		}
		
	// Forward to the dgroup forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "mod/dgroups/topicposts.php?topic={$topic_guid}&dgroup_guid={$dgroup_guid}";
	        forward($url);

?>