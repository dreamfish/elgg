<?php
	$worker=get_entity($vars['entity']->assigned_to);
	$owner = $vars['entity']->getOwnerEntity();
	$container = get_entity($vars['entity']->getContainer());
	$friendlytime = friendly_time($vars['entity']->time_created);
?>
				<table width="100%" class="tasks" >
					<tr>
						<td colspan="2">
			  				<b><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></b>
						</td>
						<td colspan="2" style="text-align: right;">
							<a href="<?php echo $vars['url']; ?>pg/tasks/<?php echo $container->username; ?>"><?php echo $container->name; ?></a>&nbsp;<?php echo $friendlytime; ?>
						</td>
					</tr>
					<tr>
						<td width="25%">
						<b><?php echo elgg_echo('tasks:task_type'); ?></b>
						<span class="type"><?php echo elgg_echo("tasks:task_type_{$vars['entity']->task_type}"); ?></span>
						</td>
						<td width="25%">
						<b><?php echo elgg_echo('tasks:status'); ?></b>
						<span class="status"><?php echo elgg_echo("tasks:task_status_{$vars['entity']->status}"); ?></span>
						</td>
						<td width="25%" style="text-align: right;">
						<b><?php echo $worker ? elgg_echo('tasks:assigned_to') :""; ?></b>
						<?php if ($worker) { ?>
						<a class="worker" href="<?php echo $vars['url']; ?>pg/profile/<?php echo $worker->username; ?>"><?php echo $worker->name; ?></a>
            <?php } ?>
						</td>
						<td width="25%" style="text-align: right;">
						<?php echo elgg_view('output/text',array('value' => elgg_echo("tasks:task_percent_done_{$vars['entity']->percent_done}"))); ?>
						<b><?php echo elgg_echo('tasks:percent_done'); ?></b>
						</td>
					</tr>
				</table>
