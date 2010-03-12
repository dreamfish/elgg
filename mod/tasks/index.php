<?php

	/**
	 * Elgg tasks plugin index page
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

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
                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
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
		
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
			
	// List tasks
		$area2 = elgg_view_title(sprintf(elgg_echo('tasks:read'), $page_owner->name));
		set_context('search');
		$status = get_input('status');	
		if ($status == '') {
			$items = get_entities('object','tasks',page_owner());
		}
		else
		{
			 
			if ($status == 'open')
				$items = get_entities_from_metadata('status', '0', 'object','tasks',page_owner(), 40);
			elseif ($status == 'closed')
				$items = get_entities_from_metadata('status', '5', 'object','tasks',page_owner(), 40);
			elseif ($status == 'info')
				$items = get_entities_from_metadata('status', '4', 'object','tasks',page_owner(), 40);
			elseif ($status == 'testing')
				$items = get_entities_from_metadata('status', '3', 'object','tasks',page_owner(), 40);
			elseif ($status == 'progress')
				$items = get_entities_from_metadata('status', '2', 'object','tasks',page_owner(), 40);
			elseif ($status == 'assigned')
				$items = get_entities_from_metadata('status', '1', 'object','tasks',page_owner(), 40);

		}
		

		sksort($items, "title");
		$area2.= elgg_view_entity_list($items, count($items), 0, 20, false, false, true);;
		
		set_context('tasks');
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		echo page_draw(elgg_echo('tasks:read'),$body);

?>
