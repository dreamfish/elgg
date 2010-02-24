<?php

	/**
	 * Elgg tasks add/save action
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	
	gatekeeper();
	action_gatekeeper();

		$title = get_input('title');
		$guid = get_input('task_guid',0);
		$description = get_input('description');
		$access = get_input('access');
		$shares = get_input('shares',array());
		
		$start_date=get_input('start_date');
		$end_date=get_input('end_date');
		$task_type = get_input('task_type',0);
		$status = get_input('status',0);
		$assigned_to = get_input('assigned_to',0);
		$percent_done = get_input('percent_done',0);
		$work_remaining = get_input('work_remaining');
		
		// Write access id
		$write_access = get_input('write_access', ACCESS_PRIVATE);
		
		$tags = get_input('tags');
		$tagarray = string_to_tag_array($tags);
		
		$page_owner = page_owner_entity();
		
		if ($guid == 0) {
			
			$entity = new ElggObject;
			$entity->subtype = "tasks";
			$entity->owner_guid = $_SESSION['user']->getGUID();
			//$entity->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
			// Petite astuce pour mieux gérer les groupes
			//$entity->owner_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
			$entity->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
		} else {
			
			$canedit = false;
			if ($entity = get_entity($guid)) {
				if ($entity->canEdit()) {
					$canedit = true;
				}
			}
			if (!$canedit) {
				system_message(elgg_echo('notfound'));
				forward("pg/tasks");
			}
			
		}
		
		$entity->title = $title;
		//$entity->address = $address;
		$entity->description = $description;
		$entity->access_id = $access;
		$entity->tags = $tagarray;
		
		$entity->start_date = $start_date;
		$entity->end_date = $end_date;
		$entity->task_type = $task_type;
		$entity->status = $status;
		$entity->assigned_to = $assigned_to;
		$entity->percent_done = $percent_done;
		$entity->work_remaining = $work_remaining;
		$entity->write_access_id = $write_access;
		
		if ($entity->save()) {
			$entity->clearRelationships();
			$entity->shares = $shares;
		
			if (is_array($shares) && sizeof($shares) > 0) {
				foreach($shares as $share) {
					$share = (int) $share;
					add_entity_relationship($entity->getGUID(),'share',$share);
				}
			}
			system_message(elgg_echo('tasks:save:success'));
			//add to river
			add_to_river('river/object/tasks/create','create',$_SESSION['user']->guid,$entity->guid);
			forward($entity->getURL());
		} else {
			register_error(elgg_echo('tasks:save:failed'));
			forward("pg/tasks");
		}

?>