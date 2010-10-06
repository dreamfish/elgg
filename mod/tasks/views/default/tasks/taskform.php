<?php

	/**
	 * Elgg tasks plugin form
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Have we been supplied with an entity?
		if (isset($vars['entity'])) {
			
			$guid = $vars['entity']->getGUID();
			$title = $vars['entity']->title;
			$description = $vars['entity']->description;
			
			$tags = $vars['entity']->tags;
			$access_id = $vars['entity']->access_id;
			
			$owner = $vars['entity']->getOwnerEntity();
			$highlight = 'default';
			
			$start_date = $vars['entity']->start_date;
			$end_date = $vars['entity']->end_date;
			$task_type = $vars['entity']->task_type;
			$status = $vars['entity']->status;
			$assigned_to = $vars['entity']->assigned_to;
			$percent_done = $vars['entity']->percent_done;
			$write_access_id = $vars['entity']->write_access_id;
			
			$container_id = $vars['entity']->getContainer();
			$container = get_entity($container_id);

			
		} else {
			
			$guid = 0;
			$title = get_input('title',"");
			$description = "";

			$highlight = 'all';
			
			if ($address == "previous")
				$address = $_SERVER['HTTP_REFERER'];
			$tags = array();
			
			// bootstrap the access permissions in the entity array so we can use defaults
			if (defined('ACCESS_DEFAULT')) {
				$vars['entity']->access_id = ACCESS_DEFAULT;
				$vars['entity']->write_access_id = ACCESS_DEFAULT;
			} else {
				$vars['entity']->access_id = 0;
				$vars['entity']->write_access_id = 0;
			}
			
			$shares = array();
			$owner = $vars['user'];
			
			//$container_id = $vars['container_guid'];
			$container_id = get_input('container_guid');
			$container = get_entity($container_id);
			
		}
		
		$assign_list = array();
		$assign_list[0] = "";
		$assign_list[$_SESSION['user']->getGUID()] = $_SESSION['user']->name;
		if($container instanceof ElggGroup){

			$assign_list1 = $container->getMembers(300);
			
			foreach($assign_list1 as $members)
				$assign_list[$members->getGUID()] = $members->name;
				
		}else{
     
      $assign_list1 = $_SESSION['user']->getFriends("", 300, $offset = 0);
      
			
			foreach($assign_list1 as $friends)
				$assign_list[$friends->getGUID()] = $friends->name;
    }	
?>
 <?php echo elgg_view('input/securitytoken'); ?>

		<table class="tasks" width="100%">
		  <tr>
				<td width="33%">
		  		<label>
			  	<?php echo elgg_echo('tasks:task_type'); ?>	
			  	<?php echo elgg_view('input/pulldown', array(
						'internalname' => 'task_type',
						'options_values' => array(
							'0' => elgg_echo('tasks:task_type_0'),
							'1' => elgg_echo('tasks:task_type_1'),
							'2' => elgg_echo('tasks:task_type_2'),
							'3' => elgg_echo('tasks:task_type_3'),
							'4' => elgg_echo('tasks:task_type_4'),
							'5' => elgg_echo('tasks:task_type_5'),
							'6' => elgg_echo('tasks:task_type_6'),
							'7' => elgg_echo('tasks:task_type_7')
             ),
							'value' => $task_type
					)); ?>
				  </label>
				</td>
				<td width="33%">
				  <label>
				  <?php echo elgg_echo('tasks:status'); ?>	
				  <?php echo elgg_view('input/pulldown', array(
							'internalname' => 'status',
							'options_values' => array(
                             '0' => elgg_echo('tasks:task_status_0'),
													   '1' => elgg_echo('tasks:task_status_1'),
													   '2' => elgg_echo('tasks:task_status_2'),
                         	   '3' => elgg_echo('tasks:task_status_3'),
                         	   '4' => elgg_echo('tasks:task_status_4')
              ),
							'value' => $status
				  )); ?>
				  </label>
				</td>
				<td width="33%">
				  <label>
				  <?php echo elgg_echo('tasks:assigned_to'); ?>	
				  <?php echo elgg_view('input/pulldown', array(
							'internalname' => 'assigned_to',
							'options_values' => $assign_list,
							'value' => $assigned_to
				  )); ?>
				  </label>
			  </td>
			</tr>
			<tr>
				<td width="33%">
					<label>
					<?php echo elgg_echo('tasks:start_date'); ?>
					<?php echo elgg_view('input/text',array(
										'internalname' => 'start_date',
										'value' => $start_date,
										'class' => 'tiny date'
					)); ?>
					</label>
				</td>
				<td width="33%">
				  <label>
					<?php echo elgg_echo('tasks:end_date'); ?>
					<?php echo elgg_view('input/text',array(
										'internalname' => 'end_date',
										'value' => $end_date,
										'class' => 'tiny date'
					)); ?>
					</label>
				</td>
				<td width="33%">
					<label>
					<?php echo elgg_echo('tasks:percent_done'); ?>
					<?php echo elgg_view('input/pulldown', array(
									'internalname' => 'percent_done',
									'options_values' => array(
                                 '0' => elgg_echo('tasks:task_percent_done_0'),
															   '1' => elgg_echo('tasks:task_percent_done_1'),
															   '2' => elgg_echo('tasks:task_percent_done_2'),
                             	   '3' => elgg_echo('tasks:task_percent_done_3'),
			                           '4' => elgg_echo('tasks:task_percent_done_4'),
			                           '5' => elgg_echo('tasks:task_percent_done_5')
                  ),
									'value' => $percent_done
					)); ?>
					</label>
					</td>
				</tr>
		</table>
