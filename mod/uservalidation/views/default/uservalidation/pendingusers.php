<?php
	/**
	 * User validation plugin.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	$friendlytime = friendly_time($vars['entity']->time_created);
	if (empty($vars['entity']->prev_last_action)) 
	{
		$status = elgg_echo('uservalidation:waiting');
	}
	else
	{
		$status = elgg_echo('uservalidation:banned');
	}
	$info .= "<p>{$status}: {$vars['entity']->name} ({$vars['entity']->username})</p>";
	$info .= '<p class="owner_timestamp">' . elgg_echo('uservalidation:registered') . ": {$friendlytime}</p>";
	$info .= '<p style="text-align:right;">';
	$info .= "<a href=\"{$CONFIG->site->url}action/uservalidation/deleteuser?u={$vars['entity']->guid}\">" . elgg_echo('uservalidation:delete') . '</a>';
	$info .= '&nbsp;&nbsp;';
	$info .= "<a href=\"{$CONFIG->site->url}action/uservalidation/activateuser?u={$vars['entity']->guid}\">" . elgg_echo('uservalidation:activate') . '</a>';
	$info .= '</p>';
	$icon = elgg_view("graphics/icon", array('size' => 'small', 'entity' => $vars['entity']));
	echo elgg_view_listing($icon, $info);
		
?>