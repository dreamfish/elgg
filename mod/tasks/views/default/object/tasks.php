<?php

	/**
	 * Elgg task view
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = friendly_time($vars['entity']->time_created);

	if (get_context() == "search") {

		if (get_input('search_viewtype') == "gallery") {

			$parsed_url = parse_url($vars['entity']->address);
			$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";
		
			$info = "<p class=\"shares_gallery_title\">". elgg_echo("tasks:shared") .": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a> (<a href=\"{$vars['entity']->address}\">".elgg_echo('tasks:visit')."</a>)</p>";
			$info .= "<p class=\"shares_gallery_user\">By: <a href=\"{$vars['url']}pg/tasks/{$owner->username}\">{$owner->name}</a> <span class=\"shared_timestamp\">{$friendlytime}</span></p>";
			$numcomments = elgg_count_comments($vars['entity']);
			if ($numcomments)
				$info .= "<p class=\"shares_gallery_comments\"><a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $numcomments . ")</a></p>";
			
			//display 
			echo "<div class=\"share_gallery_view\">";
			echo "<div class=\"share_gallery_info\">" . $info . "</div>";
			echo "</div>";


		} else {

			$parsed_url = parse_url($vars['entity']->address);
			$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";
			if (@file_exists($faviconurl)) {
				$icon = "<img src=\"{$faviconurl}\" />";
			} else {
				$icon = elgg_view(
					"profile/icon", array(
										'entity' => $owner,
										'size' => 'small',
									  )
				);
			}
		
			$info = "";//"<p class=\"shares_gallery_title\">". elgg_echo("tasks:shared") .": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a></p>";
			
			$numcomments = elgg_count_comments($vars['entity']);
			
			$info .= elgg_view('tasks/tasksresume', $vars);
			
			if ($vars['entity']->canEdit())
				$info .= "<a href=\"".$vars['url']."mod/tasks/manage.php?task=".$vars['entity']->getGUID()."\">".elgg_echo('tasks:tasksmanage')."</a>&nbsp;"; 

			if ($numcomments)
				$info .= "<a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $numcomments . ")</a>";
								
		    $info .= "</p>";
			
			echo elgg_view_listing($icon, $info);

		}
		
	} else {

?>
	<?php echo elgg_view_title(elgg_echo('tasks:shareditem'), false); ?>
	<div class="contentWrapper">
	<div class="sharing_item">
		<?php 
			echo elgg_view('tasks/tasksresume', $vars);
		?>
		<div class="sharing_item_description">
				<?php echo elgg_view('output/longtext', array('value' => $vars['entity']->description)); ?>
		</div>
		<?php

		$categories = elgg_view('categories/view', $vars);
					if (!empty($categories)) {
						echo '<p class="categories">' . $categories . '</p>';
					}
		?>
<?php

	$tags = $vars['entity']->tags;
	if (!empty($tags)) {

?>
		<div class="sharing_item_tags">
			<p>
				<?php echo elgg_view('output/tags',array('value' => $vars['entity']->tags)); ?>
			</p>
		</div>
<?php

	}

?>
		
		<?php

			if ($vars['entity']->canEdit()) {
		
		?>
		<div class="sharing_item_controls">
			<p>

				<a href="<?php echo $vars['url']; ?>mod/tasks/manage.php?task=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo('tasks:tasksmanage'); ?></a> &nbsp; 
				<a href="<?php echo $vars['url']; ?>mod/tasks/add.php?task=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a> &nbsp; 
				<?php 
						echo elgg_view('output/confirmlink',array(
						
							'href' => $vars['url'] . "action/tasks/delete?task_guid=" . $vars['entity']->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("tasks:delete:confirm"),
						
						));  
					?>
			</p>
		</div>
		<?php

			}
		
		?>
	
	</div>
	</div>
<?php

	if ($vars['full'])
		echo elgg_view_comments($vars['entity']);

?>
	
<?php

	}

?>