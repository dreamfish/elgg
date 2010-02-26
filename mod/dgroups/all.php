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

	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$tag = get_input("tag");
	$filter = get_input("filter");
	if(!$filter)
		$filter = "newest";
	
	
	// Get objects
	$context = get_context();
	
	set_context('search');
	if ($tag != "")
		$objects = list_entities_from_metadata('tags',$tag,'dgroup',"","", $limit, false);
	else{
		switch($filter){
			case "newest":
			$objects = list_entities('group',"dgroup", 0, $limit, false);
			break;
			case "pop":
			$objects = list_entities_by_relationship_count('member', 'false', 'group', 'dgroup');
			break;
			case "active":
			$objects = list_entities_from_annotations("object", "dgroupforumtopic", "dgroup_topic_post", "", 40, 0, 0, false, true);
			break;
			case 'default':
			$objects = list_entities('group',"dgroup", 0, $limit, false);
			break;
		}
	}
	
	//get a dgroup count
	$dgroup_count = get_entities("dgroup", "dgroup", 0, "", 10, 0, true, 0, null);
		
	//DISABLE find dgroups Sx: Search by tag needs to be reconstituted or removed from the left side of the people and project lists
	//$area1 = elgg_view("dgroups/find");
	
	//menu options
	$area1 .= elgg_view("dgroups/side_menu");
	
	//featured dgroups
	$featured_dgroups = get_entities_from_metadata("featured_dgroup", "yes", "dgroup", "", 0, 10, false, false, false);	
	$area1 .= elgg_view("dgroups/featured", array("featured" => $featured_dgroups));
		
		
	set_context($context);
	
	$title = sprintf(elgg_echo("dgroups:all"),page_owner_entity()->name);
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view('dgroups/contentwrapper', array('body' => elgg_view("dgroups/dgroup_sort_menu", array("count" => $dgroup_count, "filter" => $filter)) . $objects));
	$body = elgg_view_layout('sidebar_boxes',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);



?>