<?php 
	/**
	 * Elgg dgroups profile display
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	if ($vars['full']) {
		echo elgg_view("dgroups/dgroupprofile",$vars);
	} else {
		if (get_input('search_viewtype') == "gallery") {
			echo elgg_view('dgroups/dgroupgallery',$vars); 				
		} else {
			echo elgg_view("dgroups/dgrouplisting",$vars);
		}
	}
?>