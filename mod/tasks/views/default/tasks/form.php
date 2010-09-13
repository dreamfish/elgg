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
			$work_remaining = $vars['entity']->work_remaining;
			$write_access_id = $vars['entity']->write_access_id;
			
			$container_id = $vars['entity']->getContainer();
			$container = get_entity($container_id);

			
		} else {			
			$guid = 0;
			$title = get_input('title',"");
			$description = "";
			//$address = get_input('address',"");
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
			
			
			$container_id = get_input('container_guid');
			$container = get_entity($container_id);
			
		}
?>
<div class="contentWrapper">
	<form action="<?php echo $vars['url']; ?>action/tasks/add" method="post">
		<?php echo elgg_view('input/securitytoken'); ?>
		<p>
			<label>
				<?php 	//echo elgg_echo('title'); ?>
				<?php

						echo elgg_view('input/text',array(
								'internalname' => 'title',
								'value' => $title,
						)); 
				
				?>
			</label>
		</p>
		<?php echo elgg_view('tasks/taskform'); ?>
		<p>
			<label>
				<?php 	echo elgg_echo('tasks:access'); ?>
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
		</p>
		<p>
			<label>
				<?php 	echo elgg_echo('tasks:write_access'); ?>
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
		</p>
		<p class="longtext_editarea">
			<label>
				<?php 	echo elgg_echo('description'); ?>
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
				<?php 	echo elgg_echo('tags'); ?>
				<?php

						echo elgg_view('input/tags',array(
								'internalname' => 'tags',
								'value' => $tags,
						)); 
				
				?>
			</label>
		</p>
		<?php
		// Ajout de FXN pour gérer les catégories dans les tasks
		$cats = elgg_view('categories',$vars);
		if (!empty($cats)) {
		?>
			<p>
				<?php 
					echo $cats;
				?>
			</p>
		<?php
					
				}
		
		?>
		<p>
			<?php echo $vars['container_guid'] ? elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $vars['container_guid'])) : ""; ?>
			<input type="hidden" name="task_guid" value="<?php echo $guid; ?>" />
			<input type="submit" value="<?php echo elgg_echo('save'); ?>" />
		</p>
	
	</form>
</div>
