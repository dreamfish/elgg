<?php

	/**
	 * Elgg tasks plugin index page
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
			
	// List tasks
		$area2 = elgg_view_title(sprintf(elgg_echo('tasks:read'), $page_owner->name));
		set_context('search');
		$status = get_input('status');	
		if ($status == '') {
			$area2 .= list_entities('object','tasks',page_owner(), 20);
		}
		else
		{
			if ($status == 'open')
				$area2 .= list_entities_from_metadata('status', '0', 'object','tasks',page_owner(), 20);
			elseif ($status == 'closed')
				$area2 .= list_entities_from_metadata('status', '5', 'object','tasks',page_owner(), 20);
			elseif ($status == 'info')
				$area2 .= list_entities_from_metadata('status', '4', 'object','tasks',page_owner(), 20);
			elseif ($status == 'testing')
				$area2 .= list_entities_from_metadata('status', '3', 'object','tasks',page_owner(), 20);
			elseif ($status == 'progress')
				$area2 .= list_entities_from_metadata('status', '2', 'object','tasks',page_owner(), 20);
			elseif ($status == 'assigned')
				$area2 .= list_entities_from_metadata('status', '1', 'object','tasks',page_owner(), 20);

		}
		set_context('tasks');
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		echo page_draw(elgg_echo('tasks:read'),$body);

?>
