<?php

	/**
	 * Elgg dgroups: delete topic action
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
	    $dgroup_entity =  get_entity(get_input('dgroup'));
	    if (!$dgroup_entity->isMember($vars['user'])) forward();

	// Get input data
		$topic_guid = (int) get_input('topic');
		$dgroup_guid = (int) get_input('dgroup');
		
	// Make sure we actually have permission to edit
		$topic = get_entity($topic_guid);
		if ($topic->getSubtype() == "dgroupforumtopic") {
	
		// Get owning user
			//	$owner = get_entity($topic->getOwner());
		// Delete it!
				$rowsaffected = $topic->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("dgroupstopic:deleted"));
				} else {
					system_message(elgg_echo("dgroupstopic:notdeleted"));
				}
		// Forward to the dgroup forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "pg/dgroups/forum/{$dgroup_guid}/";
			forward($url);
		
		}
		
?>