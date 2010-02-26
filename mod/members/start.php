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
				
				$CONFIG->member_skills = 'Arts, Account Management / Sales, Business Services, Coaching and Mentorship, Community Management, Education and Training, Engineering - Industrial, Engineering - Software, Environmental, Design, Group Facilitation, Funding, Fundraising, Health and Wellness, Insurance, ICT - Information Communication Tech, Marketing, NGO Management, Outsourcing, Project Management, Strategy / Planning, Value and Social Impact Analysis, Virtual Assistance - Data / Contact management, Virtual Assistance - Research, Virtual Assistance - Social networking, Virtual Assistance - Scheduling / ToDo List, Virtual Assistance - Technical, Virtual Assistance - Transcription, Web hosting - Systems Administration, Writing, Other';				
			
		}
		
		// Make sure the initialisation function is called on initialisation
		register_elgg_event_handler('pagesetup','system','members_pagesetup');
		
		register_elgg_event_handler('init','system','members_init');

?>