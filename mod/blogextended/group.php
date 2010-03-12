<?php

	/**
	 * Elgg view all blog posts from all users page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		define('everyoneblog','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
// Get the current page's owner
		$page_owner = $_SESSION['user'];
		
    $group = get_input("group");
		set_context($group);
   
   $area2 = elgg_view_title(elgg_echo('blog:everyone'));

    $type = get_input("type");
    
	$pages = search_for_object('DF_'.'LabListContent');
	if ($pages && sizeof($pages) > 0) {
		$toptext .= $pages[0]->description;
	} 



	$area2 = $toptext .  list_entities_from_metadata_multi(array("content_owner"=>$group, "blog_type"=>$type), "object","blog",0, 10, false,false,false);

    //search_for_group
    $objects = get_entities("group", "", 0, "", 10, 0, 0, 0, null);
    //$objects = get_entities_from_metadata_multi(array('content_owner'=>3), 'object', 'blog');
    $objects = get_entities_from_metadata("blogposts_enable", "yes", "group");
    foreach($objects as $o) 
    {      
      //add_submenu_item($o->name, $url . 'group.php?group=' .$o->guid);
    }
  
    global $CONFIG;
    foreach($CONFIG->BLOG_TYPES as $key => $option)
    {
      add_submenu_item($option, $url . 'group.php?group=' .$o->guid . '&type=' . $key);
    }
	  
    
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);
		
	// Display page  
		page_draw(elgg_echo('blog:everyone'),$body);
		
?>