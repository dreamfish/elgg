<?php
	/**
	 * Invite users to dgroups
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
	
	$dgroup_guid = (int) get_input('dgroup_guid');
	$dgroup = get_entity($dgroup_guid);
	set_page_owner($dgroup_guid);

	$title = elgg_echo("dgroups:invite");

	$area2 = elgg_view_title($title);
	
	if (($dgroup) && ($dgroup->canEdit()))
	{	
		$area2 .= elgg_view("forms/dgroups/invite", array('entity' => $dgroup));
			 
	} else {
		$area2 .= elgg_echo("dgroups:noaccess");
	}
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>