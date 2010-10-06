<?php

	/**
	 * Elgg tasks plugin
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// tasks initialisation function
		function tasks_init() {
			
			// Grab the config file
			global $CONFIG;
			
			extend_view('metatags','tasks/js');
			//add a tools menu option
			if (isloggedin())
	 	        add_menu(elgg_echo('tasks'), $CONFIG->wwwroot . "pg/tasks/" . $_SESSION['user']->username . '/items');
				
			// Register a page handler, so we can have nice URLs
				register_page_handler('tasks','tasks_page_handler');
				
			// Add our CSS
				extend_view('css','tasks/css');
			// Add to groups context
				extend_view('groups/left_column', 'tasks/groupprofile_tasks'); 
				
			// Register granular notification for this type
			if (is_callable('register_notification_object'))
				register_notification_object('object', 'tasks', elgg_echo('tasks:new'));

			// Listen to notification events and supply a more useful message
			   register_plugin_hook('notify:entity:message', 'object', 'tasks_notify_message');
			
			// Register a URL handler for shared items
				register_entity_url_handler('task_url','object','tasks');
				
			// Shares widget
			    add_widget_type('tasks',elgg_echo("tasks:recent"),elgg_echo("tasks:widget:description"));
				
			// Register entity type
				register_entity_type('object','tasks');
				
			// Add group menu option
				add_group_tool_option('tasks',elgg_echo('tasks:enabletasks'),true);
			    
		}
		
		function tasks_pagesetup() {
			global $CONFIG;
			
		// Set up menu for logged in users
			
			//add submenu options
				if (get_context() == "tasks") {
					if (isloggedin()) {
						if (page_owner()) {
							$page_owner = page_owner_entity();
							
              add_submenu_item(sprintf(elgg_echo('tasks:read'), $page_owner->name),$CONFIG->wwwroot."pg/tasks/" . $page_owner->username . "/items");
              
              add_submenu_item("Closed ".sprintf(elgg_echo('tasks:read'), $page_owner->name),$CONFIG->wwwroot."pg/tasks/" . $page_owner->username . "/items/closed");
              

						}
					}					
					if(!$page_owner instanceof ElggGroup)
						add_submenu_item(elgg_echo('tasks:everyone'),$CONFIG->wwwroot."mod/tasks/everyone.php");

					if ((isloggedin()) && (page_owner()) && (can_write_to_container(0, page_owner()))) {
						$page_owner = page_owner_entity();
						// Ajout de Fx pour créer des tasks vierges
						add_submenu_item(sprintf(elgg_echo("tasks:add"),$page_owner->name), $CONFIG->wwwroot . "pg/tasks/" . $page_owner->username . '/add'.'?container_guid='.$page_owner->getGUID());

					}
						
				}
				
				$page_owner = page_owner_entity();
				
				if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
	    			if($page_owner->tasks_enable != "no"){
					    add_submenu_item(sprintf(elgg_echo("tasks:group"),$page_owner->name), $CONFIG->wwwroot . "pg/tasks/" . $page_owner->username . '/items');					    
				    }
				}
				
		}
		
		/**
		 * tasks page handler; allows the use of fancy URLs
		 *
		 * @param array $page From the page_handler function
		 * @return true|false Depending on success
		 */
		function tasks_page_handler($page) {
			// The first component of a tasks URL is the username
			if (isset($page[0])) {				
				set_input('username',$page[0]);
			}
			
			// The second part dictates what we're doing
			if (isset($page[1])) {
				switch($page[1]) {
					case "read":		set_input('guid',$page[2]);
										include(dirname(dirname(dirname(__FILE__))) . "/entities/index.php"); 
										break;
					case "items":		
										if (isset($page[2])) 
										{
											set_input('status', $page[2]);
										}
										include(dirname(__FILE__) . "/index.php"); //return true;
										break;
					case "add": 		include(dirname(__FILE__) . "/add.php"); //return true;
										break;
					case "manage": 		include(dirname(__FILE__) . "/manage.php"); //return true;
										break;
					case "table":
										$area2 .= elgg_view_title('All Tasks', false);
										$area2 .= elgg_view('tasks/table');
										$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
										echo page_draw(elgg_echo('tasks:tasksmanageone'),$body);
										return true;
										break;
					default:			include(dirname(__FILE__) . "/index.php"); //return true;
										break;			
				}
			// If the URL is just 'tasks/username', or just 'tasks/', load the standard tasks index
			} else {
				include(dirname(__FILE__) . "/index.php");				
			}	
			return true;		
		}

	/**
	 * Populates the ->getUrl() method for tasked objects
	 *
	 * @param ElggEntity $entity The tasked object
	 * @return string tasked item URL
	 */
		function task_url($entity) {
			global $CONFIG;
			$title = $entity->title;
			$title = friendly_title($title);
			return $CONFIG->url . "pg/tasks/" . $entity->getOwnerEntity()->username . "/read/" . $entity->getGUID() . "/" . $title;
			
		}
		
	    /**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
	    */
		function tasks_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'tasks'))
			{
				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $CONFIG->wwwroot . "pg/view/" . $entity->guid;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("tasks:via") . ': ' . $url . ' (' . $title . ')';
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("tasks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
				if ($method == 'web') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ' ' . elgg_echo("tasks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}

			}
			return null;
		}

	/**
	 * Extend permissions checking to extend can-edit for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function tasks_write_permission_check($hook, $entity_type, $returnvalue, $params)
	{
		if ($params['entity']->getSubtype() == 'tasks') {
		
			$write_permission = $params['entity']->write_access_id;
			$user = $params['user'];

			if (($write_permission) && ($user))
			{
				// $list = get_write_access_array($user->guid);
				$list = get_access_array($user->guid); // get_access_list($user->guid);
					
				if (($write_permission!=0) && (in_array($write_permission,$list)))
					return true;
				
			}
		}
	}
	
	/**
	 * Extend container permissions checking to extend can_write_to_container for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function tasks_container_permission_check($hook, $entity_type, $returnvalue, $params) {
		
		if (get_context() == "tasks") {
			if (page_owner()) {
				if (can_write_to_container($_SESSION['user']->guid, page_owner())) return true;
			}
			if ($page_guid = get_input('page_guid',0)) {
				$entity = get_entity($page_guid);
			} else if ($parent_guid = get_input('parent_guid',0)) {
				$entity = get_entity($parent_guid);
			}
			if ($entity instanceof ElggObject) {
				if (
						can_write_to_container($_SESSION['user']->guid, $entity->container_guid)
						|| in_array($entity->write_access_id,get_access_list())
					) {
						return true;
				}
			}
		}
		
	}
	
	// write permission plugin hooks
	register_plugin_hook('permissions_check', 'object', 'tasks_write_permission_check');
	register_plugin_hook('container_permissions_check', 'object', 'tasks_container_permission_check');

		
	// Make sure the initialisation function is called on initialisation
	register_elgg_event_handler('init','system','tasks_init');
	register_elgg_event_handler('pagesetup','system','tasks_pagesetup');

	// Register actions
	global $CONFIG;
	register_action('tasks/add',false,$CONFIG->pluginspath . "tasks/actions/add.php");
	register_action('tasks/manage',false,$CONFIG->pluginspath . "tasks/actions/manage.php");
	register_action('tasks/delete',false,$CONFIG->pluginspath . "tasks/actions/delete.php");

?>
