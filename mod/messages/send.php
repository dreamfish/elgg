<?php

	/**
	 * Elgg send a message page
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		gatekeeper(); // if (!isloggedin()) forward();
		
    // Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		} 
		
    // Get the users friends; this is used in the drop down to select who to send the message to
         //$friends = $_SESSION['user']->getFriends('', 9999);
         //$friends = get_entities('user', '', 0, 'username', 9999);
              
            $query = "SELECT DISTINCT u.*  FROM {$CONFIG->dbprefix}users_entity u order by u.name";

            $friends = get_data($query, "entity_row_to_elggstar");

        
    // Set the page title
	    $area2 = elgg_view_title(elgg_echo("messages:sendmessage"));
        
    // Get the send form
		$area2 .= elgg_view("messages/forms/message",array('friends' => $friends));

	// Format
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw page
		page_draw(sprintf(elgg_echo('messages:send'),$page_owner->name),$body);
		
?>