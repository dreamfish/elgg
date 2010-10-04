<?php

	/**
	 * Elgg tasks plugin index page
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
   *
   * Modified by Jillian Burrows von Dreamfish
	 */

  function sksort(&$array, $subkey="id", $sort_ascending=false) {

    if (count($array))
        $temp_array[key($array)] = array_shift($array);

      foreach($array as $key => $val){
        $offset = 0;
        $found = false;
        foreach($temp_array as $tmp_key => $tmp_val)
          {
            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
              {
                $temp_array = array_merge(
                  (array)array_slice($temp_array,0,$offset),
                  array($key => $val),
                  array_slice($temp_array,$offset)
                );
                $found = true;
              }
            $offset++;
          }
        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
      }

    if ($sort_ascending) $array = array_reverse($temp_array);

    else $array = $temp_array;
  }

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		global $CONFIG;
		$url = $CONFIG->wwwroot;
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}

	// List tasks
		$context = get_context();
		$title = sprintf(elgg_echo('tasks:read'), $page_owner->name);
		$area2 = elgg_view_title($title);
    $area2 .= elgg_view('tasks/sorter', array());
		set_context('tasks');


		$items = get_entities('object','tasks',page_owner(),'', 1000);

    if ($items) { 
		  sksort($items, "title", true);
    }
    foreach ($items as $item) {
  		$area2 .= "<div class=\"contentWrapper task\">";
      $area2 .= elgg_view('tasks/tasksresume', array('entity' => $item));
      $area2 .= "<a href=\"{$url}mod/tasks/manage.php?task=".$item->getGUID()."\">".elgg_echo('tasks:tasksmanage')."</a>";
      $area2 .="</div>";
    }
		set_context($context);

	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

	// Draw it
		echo page_draw($title,$body);

?>
