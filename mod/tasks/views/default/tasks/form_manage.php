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
			$shares = $vars['entity']->shares;
			$owner = $vars['entity']->getOwnerEntity();
			$highlight = 'default';
			
			$start_date = $vars['entity']->start_date;
			$end_date = $vars['entity']->end_date;
			$task_type = $vars['entity']->task_type;
			$status = $vars['entity']->status;
			$assigned_to = $vars['entity']->assigned_to;
			$percent_done = $vars['entity']->percent_done;
			$work_remaining = $vars['entity']->work_remaining;
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
			
			$container_id = $vars['container_guid'];
			$container = get_entity($container_id);
			
		}
		
		$assign_list = array();
		$assign_list[0] = "";
		$assign_list[$_SESSION['user']->getGUID()] = $_SESSION['user']->name;
		if($container instanceof ElggGroup){
				
			$assign_list1 = $container->getMembers();
			
			foreach($assign_list1 as $members)
				$assign_list[$members->getGUID()] = $members->name;
				
		}	

?>

	<form action="<?php echo $vars['url']; ?>action/tasks/manage" method="post">
		<?php echo elgg_view('input/securitytoken'); ?>
		<div class="contentWrapper">
		<h2>
			<? echo elgg_view('input/text',array(
								'internalname' => 'title',
								'value' => $title,
						)); 
			?>
		</h2>
		<br/>
		<?php echo elgg_view('tasks/taskform',$vars); ?>
		<p>
		<table class="tasks" width="100%">
			<tr>
				<td width="33%">
					<label>
						<?php echo elgg_echo('tasks:access'); ?>
						<?php
								$access = $access_id;
								if (!$access) 
								{
									$access = ACCESS_PUBLIC;
								} 
								echo elgg_view('input/access',array(
										'internalname' => 'access',
										'value' => $access,
								)); 
						?>
					</label>
				</td>
				<td width="33%">
					<label>
						<?php echo elgg_echo('tasks:write_access'); ?>
						<?php
								$write_access = $write_access_id;
								if (!$write_access) 
								{
									$write_access = ACCESS_LOGGED_IN;
								} 
								echo elgg_view('input/access',array(
										'internalname' => 'write_access',
										'value' => $write_access,
								)); 
						?>
					</label>
				</td>
				<td width="30%">
				</td>
			</tr>
		</table>
		</p>
		<p class="longtext_editarea">
			<label>
				<?php echo elgg_echo('description'); ?>
				<br />
				<?php

						echo elgg_view('input/longtext',array(
								'internalname' => 'description',
								'value' => $description,
								
						)); 
				
				?>
			</label>
		</p>
		<p>
			<label>
				<?php echo elgg_echo('tags'); ?>
				<?php

						echo elgg_view('input/tags',array(
								'internalname' => 'tags',
								'value' => $tags,
						)); 
				
				?>
			</label>
		</p>
		</div>
		<?php echo elgg_view_comments($vars['entity']);	?>
	</form>
