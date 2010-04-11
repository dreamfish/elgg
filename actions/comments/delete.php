<?php

	/**
	 * Elgg delete comment action
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <curverider.co.uk>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Ensure we're logged in
		if (!isloggedin()) forward();
		
	// Make sure we can get the comment in question
		$annotation_id = (int) get_input('annotation_id');
		if ($comment = get_annotation($annotation_id)) {
    		
    		$entity = get_entity($comment->entity_guid);
			
			if ($comment->canEdit()) {
				$comment->delete();
				system_message(elgg_echo("generic_comment:deleted"));
				forward($entity->getURL());
			}
			
		} else {
			$url = "";
		}
		
		register_error(elgg_echo("generic_comment:notdeleted"));
		forward($entity->getURL());

?>