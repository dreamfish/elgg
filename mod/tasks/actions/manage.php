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

	global $CONFIG;
	
		$title = get_input('title');
		$guid = get_input('entity_guid',0);
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
		
		$page_owner = page_owner_entity();
		
			
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

		$entity->title = $title;
		$entity->description = $description;
		$entity->access_id = $access;
		$entity->tags = $tagarray;
		
		$entity->start_date = $start_date;
		$entity->end_date = $end_date;
		$entity->task_type = $task_type;
		$entity->status = $status;
		
		$reassigned = false;
		if ($entity->assigned_to != $assigned_to ) {
			$reassigned = true;
		}
		
		
		$entity->assigned_to = $assigned_to;
		$entity->percent_done = $percent_done;
		$entity->work_remaining = $work_remaining;
		$entity->write_access_id = $write_access;
		if ($entity->save()) {
			
			system_message(elgg_echo('tasks:save:success'));
			//add to river
			add_to_river('river/object/tasks/create','create',$_SESSION['user']->guid,$entity->guid);
			
			if ($reassigned) {
				$container = get_entity($entity->container_guid);
				$subject = "Assigned task {$title} on {$container->name}";
				$body = "{$_SESSION['user']->name} assigned you to {$title} in {$container->name} <br><br>{$comment_text}<br><br>{$CONFIG->url}pg/tasks/a/read/{$entity->guid}<br>";
				
				notify_user($entity->assigned_to, $_SESSION['user']->guid, $subject, $body);
			}
			
			$comment_text = get_input('generic_comment');

			if (trim($comment_text) !== "") {
				
		        // If posting the comment was successful, say so
					if ($entity->annotate('generic_comment',$comment_text,$entity->access_id, $_SESSION['guid'])) {
						
						if ($entity->owner_guid != $_SESSION['user']->getGUID())
						notify_user($entity->owner_guid, $_SESSION['user']->getGUID(), elgg_echo('generic_comment:email:subject'), 
							sprintf(
										elgg_echo('generic_comment:email:body'),
										$entity->title,
										$_SESSION['user']->name,
										$comment_text,
										$entity->getURL(),
										$_SESSION['user']->name,
										$_SESSION['user']->getURL()
									)
						); 
						
						system_message(elgg_echo("generic_comment:posted"));
						//add to river
						add_to_river('annotation/annotate','comment',$_SESSION['user']->guid,$entity->guid);
	
						
					} else {
						register_error(elgg_echo("generic_comment:failure"));
					}
			}
			forward($entity->getURL());
			
		} else {
			register_error(elgg_echo('tasks:save:failed'));
			forward("pg/tasks");
		}

?>
