<?php
	/**
	 * Elgg admin functions.
	 * Functions for adding and manipulating options on the admin panel.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd 
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	
	/**
	 * Register an admin page with the admin panel.
	 * This function extends the view "admin/main" with the provided view. This view should provide a description
	 * and either a control or a link to.
	 * 
	 * Usage:
	 * 	- To add a control to the main admin panel then extend admin/main
	 *  - To add a control to a new page create a page which renders a view admin/subpage (where subpage is your new page - 
	 *    nb. some pages already exist that you can extend), extend the main view to point to it, and add controls to your 
	 * 	  new view.
	 * 
	 * At the moment this is essentially a wrapper around extend_view.
	 * 
	 * @param string $new_admin_view The view associated with the control you're adding  
	 * @param string $view The view to extend, by default this is 'admin/main'.
	 * @param int $priority Optional priority to govern the appearance in the list.
	 */
	function extend_elgg_admin_page( $new_admin_view, $view = 'admin/main', $priority = 500)
	{
		return extend_view($view, $new_admin_view, $priority);
	}
	
	/**
	 * Initialise the admin page.
	 */
	function admin_init()
	{
		// Add plugin main menu option (last)
		extend_elgg_admin_page('admin/main_opt/statistics', 'admin/main');
		extend_elgg_admin_page('admin/main_opt/site', 'admin/main'); 
		extend_elgg_admin_page('admin/main_opt/user', 'admin/main'); 
		extend_elgg_admin_page('admin/main_opt/plugins', 'admin/main', 999); // Always last

		register_action('admin/user/ban', false, "", true);
		register_action('admin/user/unban', false, "", true);
		register_action('admin/user/delete', false, "", true);
		register_action('admin/user/resetpassword', false, "", true);
		register_action('admin/user/makeadmin', false, "", true);
		register_action('admin/user/removeadmin', false, "", true);
		
		// Register some actions
		register_action('admin/site/update_basic', false, "", true); // Register basic site admin action
		
		// Page handler
		register_page_handler('admin','admin_settings_page_handler');
		
		if (isadminloggedin()) {
			global $is_admin;
			$is_admin = true;
		}
		
	}
	
	function admin_pagesetup() {
		
		if (get_context() == 'admin') {
			
			global $CONFIG;
			add_submenu_item(elgg_echo('admin:statistics'), $CONFIG->wwwroot . 'pg/admin/statistics/');
			add_submenu_item(elgg_echo('admin:site'), $CONFIG->wwwroot . 'pg/admin/site/');
			add_submenu_item(elgg_echo('admin:user'), $CONFIG->wwwroot . 'pg/admin/user/');
			add_submenu_item(elgg_echo('admin:plugins'), $CONFIG->wwwroot . 'pg/admin/plugins/');
			
		}
		
	}
	
	function admin_settings_page_handler($page)
	{
		global $CONFIG;
		
		$path = $CONFIG->path . "admin/index.php";
		
		if ($page[0])
		{
			switch ($page[0])
			{
				case 'user' : $path = $CONFIG->path . "admin/user.php"; break;
				case 'statistics' : $path = $CONFIG->path . "admin/statistics.php"; break;
				case 'plugins' : $path = $CONFIG->path . "admin/plugins.php"; break;
				case 'site' : $path = $CONFIG->path . "admin/site.php"; break;
			}
		}
		
		if ($page[1])
			set_input('username', $page[1]);
			
		include($path);
	}
	

	/**
	 * Admin permissions system
	 *
	 * @return true|null True if the current user is an admin.
	 */
	function admin_permissions($hook, $type, $returnval, $params) {
		
		if (is_array($params) && !empty($params['user']) && $params['user'] instanceof ElggUser) {
			$admin = $params['user']->admin;
			if ($admin) {
				return true;
			}
		}
		
	}
	
	/**
	 * Write a persistent message to the administrator's notification window.
	 * 
	 * Currently this writes a message to the admin store, we may want to come up with another way at some point.
	 *
	 * @param string $subject Subject of the message
	 * @param string $message Body of the message
	 */
	function send_admin_message($subject, $message)
	{
		$subject = sanitise_string($subject);
		$message = sanitise_string($message);
		
		if (($subject) && ($message))
		{
			$admin_message = new ElggObject();
			$admin_message->subtype = 'admin_message';
			$admin_message->access_id = ACCESS_PUBLIC;
			$admin_message->title = $subject;
			$admin_message->description = $message;
			
			return $admin_message->save();
		}
		
		return false;
	}
	
	/**
	 * List all admin messages.
	 *
	 * @param int $limit Limit
	 */
	function list_admin_messages($limit = 10)
	{
		return list_entities('object','admin_message',0,$limit);
	}
	
	/**
	 * Remove an admin message.
	 *
	 * @param int $guid The 
	 */
	function clear_admin_message($guid)
	{
		return delete_entity($guid);
	}
	
	/// Register init functions
	register_elgg_event_handler('init','system','admin_init');
	register_elgg_event_handler('pagesetup','system','admin_pagesetup');
	
	// Register a plugin hook for permissions
	register_plugin_hook('permissions_check','all','admin_permissions');
	register_plugin_hook('container_permissions_check','all','admin_permissions');
	
?>
