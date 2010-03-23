<?php

/**
 * Elgg Vanilla Forum widget
 *
 * @package vanillaforum
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Curverider Ltd. 2009
 * @link http://elgg.org/
 *
 */

    //the number of posts to display
	$num = (int) $vars['entity']->num_display;
	if (!$num)
		$num = 5;
		
    // Display the discussion posts
    
	echo "<div id=\"widget_vanilla\">";
    
	echo elgg_view('vanillaforum/latest_discussions',array('limit'=>$num));
	
	echo "</div>";
	
?>