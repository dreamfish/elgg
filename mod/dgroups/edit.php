<?php
	/**
	 * Elgg dgroups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();

	$dgroup_guid = get_input('dgroup_guid');
	$dgroup = get_entity($dgroup_guid);
	set_page_owner($dgroup_guid);

	$title = elgg_echo("dgroups:edit");
	$body = elgg_view_title($title);
	
	if (($dgroup) && ($dgroup->canEdit()))
	{
		$body .= elgg_view("forms/dgroups/edit", array('entity' => $dgroup));
			 
	} else {
		$body .= elgg_view('dgroups/contentwrapper',array('body' => elgg_echo('dgroups:noaccess')));
	}
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $body);
	
	page_draw($title, $body);
?>