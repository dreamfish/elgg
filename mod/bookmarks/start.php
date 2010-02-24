<?php

	/**
	 * Elgg Bookmarks plugin
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Bookmarks initialisation function
		function bookmarks_init() {
			
			// Grab the config file
				global $CONFIG;
			
			// Set up menu for logged in users
				if (isloggedin()){
					add_menu(elgg_echo('bookmarks'), $CONFIG->wwwroot . "pg/bookmarks/" . $_SESSION['user']->username . '/items');	
			// And for logged out users
				} else {
					add_menu(elgg_echo('bookmarks'), $CONFIG->wwwroot . "mod/bookmarks/everyone.php");
				}

			//add submenu options
				if (get_context() == "bookmarks") {
					add_submenu_item(elgg_echo('bookmarks:inbox'),$CONFIG->wwwroot."pg/bookmarks/" . $_SESSION['user']->username . "/inbox");
					add_submenu_item(elgg_echo('bookmarks:read'),$CONFIG->wwwroot."pg/bookmarks/" . $_SESSION['user']->username . "/items");
					add_submenu_item(elgg_echo('bookmarks:bookmarklet'), $CONFIG->wwwroot . "mod/bookmarks/bookmarklet.php");
					add_submenu_item(elgg_echo('bookmarks:friends'),$CONFIG->wwwroot."pg/bookmarks/" . $_SESSION['user']->username . "/friends");
					add_submenu_item(elgg_echo('bookmarks:everyone'),$CONFIG->wwwroot."mod/bookmarks/everyone.php");
				}
				
			// Register a page handler, so we can have nice URLs
				register_page_handler('bookmarks','bookmarks_page_handler');
				
			// Add our CSS
				extend_view('css','bookmarks/css');
				
			// Register granular notification for this type
			if (is_callable('register_notification_object'))
				register_notification_object('object', 'bookmarks', elgg_echo('bookmarks:new'));

			// Listen to notification events and supply a more useful message
			   register_plugin_hook('notify:entity:message', 'object', 'bookmarks_notify_message');
			
			// Register a URL handler for shared items
				register_entity_url_handler('bookmark_url','object','bookmarks');
				
			// Shares widget
			    add_widget_type('bookmarks',elgg_echo("bookmarks:recent"),elgg_echo("bookmarks:widget:description"));
				
			// Register entity type
				register_entity_type('object','bookmarks');
			    
		}
		
		/**
		 * Bookmarks page handler; allows the use of fancy URLs
		 *
		 * @param array $page From the page_handler function
		 * @return true|false Depending on success
		 */
		function bookmarks_page_handler($page) {
			
			// The first component of a bookmarks URL is the username
			if (isset($page[0])) {
				set_input('username',$page[0]);
			}
			
			// The second part dictates what we're doing
			if (isset($page[1])) {
				switch($page[1]) {
					case "read":		set_input('guid',$page[2]);
										@include(dirname(dirname(dirname(__FILE__))) . "/entities/index.php"); return true;
										break;
					case "friends":		@include(dirname(__FILE__) . "/friends.php"); return true;
										break;
					case "inbox":		@include(dirname(__FILE__) . "/inbox.php"); return true;
										break;
					case "items":		@include(dirname(__FILE__) . "/index.php"); return true;
										break;
				}
			// If the URL is just 'bookmarks/username', or just 'bookmarks/', load the standard bookmarks index
			} else {
				@include(dirname(__FILE__) . "/index.php");
				return true;
			}
			
			return false;
			
		}

	/**
	 * Populates the ->getUrl() method for bookmarked objects
	 *
	 * @param ElggEntity $entity The bookmarked object
	 * @return string bookmarked item URL
	 */
		function bookmark_url($entity) {
			
			global $CONFIG;
			$title = $entity->title;
			$title = friendly_title($title);
			return $CONFIG->url . "pg/bookmarks/" . $entity->getOwnerEntity()->username . "/read/" . $entity->getGUID() . "/" . $title;
			
		}
		
	    /**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
	    */
		function bookmarks_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'bookmarks'))
			{
				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $CONFIG->wwwroot . "pg/view/" . $entity->guid;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("bookmarks:via") . ': ' . $url . ' (' . $title . ')';
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("bookmarks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
				if ($method == 'web') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("bookmarks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}

			}
			return null;
		}

		
	// Make sure the initialisation function is called on initialisation
		register_elgg_event_handler('init','system','bookmarks_init');

	// Register actions
		global $CONFIG;
		register_action('bookmarks/add',false,$CONFIG->pluginspath . "bookmarks/actions/add.php");
		register_action('bookmarks/delete',false,$CONFIG->pluginspath . "bookmarks/actions/delete.php");

?>