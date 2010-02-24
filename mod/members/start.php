<?php

	/**
	 * Elgg members plugin
	 * This plugin has some interesting options for users; see who is online, site members, 
	 * 
	 * @package Elggmembers
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	
		function members_init() {
    		
    		// Load system configuration
				global $CONFIG;
								
				extend_view('css','members/css');
				
				
    	    // Set up menu for logged in users
				if (isloggedin()) {
					add_menu(elgg_echo('Members'), $CONFIG->wwwroot . "mod/members/index.php");
				}
			
		}
		
		// Make sure the initialisation function is called on initialisation
		register_elgg_event_handler('pagesetup','system','members_pagesetup');
		
		register_elgg_event_handler('init','system','members_init');

?>