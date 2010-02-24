<?php
	/**
	 * Full dgroup profile
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$dgroup_guid = get_input('dgroup_guid');
	set_context('dgroups');
	
	global $autofeed;
	$autofeed = true;
	
	$dgroup = get_entity($dgroup_guid);
	if ($dgroup) {
		set_page_owner($dgroup_guid);
		
		$title = $dgroup->name;
		
		// Hide some items from closed dgroups when the user is not logged in.
		$view_all = true;
		
		$dgroupaccess = group_gatekeeper(false);
		if (!$dgroupaccess)
			$view_all = false;
		
		
		$area2 = elgg_view_title($title);
		$area2 .= elgg_view('dgroup/dgroup', array('entity' => $dgroup, 'user' => $_SESSION['user'], 'full' => true));
		
		if ($view_all) {
			//dgroup profile 'items' - these are not real widgets, just contents to display
			$area2 .= elgg_view('dgroups/profileitems',array('entity' => $dgroup));
			
			//dgroup members
			$area3 = elgg_view('dgroups/members',array('entity' => $dgroup));
		}
		else
		{
			$area2 .= elgg_view('dgroups/closedmembership', array('entity' => $dgroup, 'user' => $_SESSION['user'], 'full' => true));

		}
		
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2, $area3);
	} else {
		$title = elgg_echo('dgroups:notfound');
		
		$area2 = elgg_view_title($title);
		$area2 .= elgg_view('dgroups/contentwrapper',array('body' => elgg_echo('dgroups:notfound:details')));
		
		$body = elgg_view_layout('two_column_left_sidebar', "", $area2,"");
	}
		
	// Finally draw the page
	page_draw($title, $body);
?>