<?php

	/**
	 * Elgg title element
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['title'] The page title
	 */

	$page_owner = page_owner();
	$page_owner_user = get_entity($page_owner);

	$submenu = get_submenu(); // elgg_view('canvas_header/submenu');
	if (!empty($submenu)) $submenu = "<ul>" . $submenu . "</ul>";
	
	if (($_SESSION['guid']) && ($page_owner && $page_owner_user->guid != $_SESSION['user']->getGUID())) {
		$info = "<h2>" . $vars['title'] . "</h2>";
		if($page_owner_user instanceOf ElggGroup) { 
			$display = "<div id=\"content_area_group_title\">" . $info . "</div>";
		} else {
			$display = "<div id=\"content_area_user_title\">" . $info . "</div>";
		}
		if (!empty($submenu) && $vars['submenu'] == true)
			$display .= "<div id=\"owner_block_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
	} else {
		$info = "<h2>" . $vars['title'] . "</h2>";
		if($page_owner_user instanceOf ElggGroup) { 
			$display = "<div id=\"content_area_group_title\">" . $info . "</div>";
		} else {
			$display = "<div id=\"content_area_user_title\">" . $info . "</div>";
		}
		if (!empty($submenu)  && $vars['submenu'] == true)
			$display .= "<div id=\"owner_block_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
	}


	//print to screen
		echo $display;



?>