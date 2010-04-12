<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009 - 2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$tag = get_input("tag");
	
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	
	// Get objects
	$area2 = elgg_view_title($title = elgg_echo('file:type:all'));
	$area1 = get_filetype_cloud(); // the filter
	set_context('search');
	if ($tag != "")
		$area2 .= list_entities_from_metadata('tags',$tag,'object','file');
	else
		$area2 .= list_entities('object','file');
	set_context('file');
		
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);

	// Finally draw the page
	page_draw(sprintf(elgg_echo("file:yours"),$_SESSION['user']->name), $body);
?>