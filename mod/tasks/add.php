<?php

	/**
	 * Elgg tasks plugin add task page
	 * 
	 * @package Elggtasks
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
		if ($page_owner instanceof ElggGroup)
			$container = $page_owner->guid;
			
		$area2 .= elgg_view_title(elgg_echo('tasks:this'), false);
		
	// If we've been given a task to edit, grab it
		if ($this_guid = get_input('task',0)) {
			$entity = get_entity($this_guid);
			if ($entity->canEdit()) {
				$area2 .= elgg_view('tasks/form',array('entity' => $entity, 'container_guid' => $container));
			}
		} else {
			$area2 .= elgg_view('tasks/form', array('container_guid' => $container));
		}
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		echo page_draw(elgg_echo('tasks:add'),$body);

?>