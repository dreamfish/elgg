<?php

	/**
	 * Elgg bookmarks plugin add bookmark page
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// You need to be logged in for this one
		gatekeeper();
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
			
		$area2 .= elgg_view_title(elgg_echo('bookmarks:this'), false);
		
	// If we've been given a bookmark to edit, grab it
		if ($this_guid = get_input('bookmark',0)) {
			$entity = get_entity($this_guid);
			if ($entity->canEdit()) {
				$area2 .= elgg_view('bookmarks/form',array('entity' => $entity));
			}
		} else {
			$area2 .= elgg_view('bookmarks/form');
		}
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		echo page_draw(elgg_echo('bookmarks:add'),$body);

?>