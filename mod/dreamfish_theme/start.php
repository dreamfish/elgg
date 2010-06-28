<?php
	/**
	 * Elgg dreamfish_theme plugin
	 * This plugin plugs the dreamfish theme into elgg
	 *
	 * @package Customdash
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Astrid Johannson
	 * @copyright dreamfish.com 2010
	 */
	
	function dreamfish_theme_init() {
		register_plugin_hook('index','system','new_index');
		register_page_handler('dashboard','new_dashboard');
		register_plugin_hook('permissions_check', 'all', 'dreamfish_permissions_check');
		add_group_tool_option('blogposts','Enable Blog Posts',true);
		register_elgg_event_handler('pagesetup','system','df_pagesetup');
	
		// register plugin hook for user registration since this is modified by this module
		register_plugin_hook('action', 'register', 'registration_hook')
		register_elgg_event_handler('create','user','user_created_handler');	
		
		//unregister_elgg_event_handler('create','friend','relationship_notification_hook');
		//register_elgg_event_handler('create','friend','dfrelationship_notification_hook');
		//register_action('register', true, $CONFIG->pluginspath . "dreamfish_theme/actions/register.php");
	
		extend_view('profile/menu/links','usermenu');
	
		// inserts dreamfish-specific form elements and JS on the registration page.
		//extend_view('account/forms/register', 'forms/register');
	
		// Extend system CSS with our own styles
		extend_view('css','dreamfish_theme/css');
	
		//the following tags need to be added to \engine\lib\input.php's allowedtags
		/*
		'object' => array('height'=>array(), 'width'=>array()),
		'param' => array('name'=>array(), 'value'=>array()),
		'embed' => array('allowfullscreen'=>array(), 'allowscriptaccess'=>array(),
		'height'=>array(), 'src' => array(), 'type'=>array(), 'width'=>array()))
		*/
	
		//the following modification was required to messages/send.php
		//$friends = get_entities('user', '', 0, 'name', 9999);
	}
	
	function df_pagesetup() {
		global $CONFIG;
	
		$page_owner = page_owner_entity();
			
		if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
			//$meta = get_metadata_for_entity($page_owner->guid);
			$meta = get_metadata_byname($page_owner->guid, 'blogposts_enable');
			if($meta->value == "yes"){
				add_submenu_item("Write Blog", $CONFIG->wwwroot . "mod/blogextended/add.php?group=" . $page_owner->guid);
				add_submenu_item("View Blog", $CONFIG->wwwroot . "mod/blogextended/group.php?group=" . $page_owner->guid);
			}
		}
	}
	

	function new_dashboard($page) {
		dreamfish_theme_fetchpage(array('MemberDashboard'));
	}
	
	function new_index() {
		//if (!@include_once(dirname(dirname(__FILE__))) . "/dreamfish_theme/index.php") return false;
		//return true;
		dreamfish_theme_fetchpage(array('Home'));
		return true;
	}
	
	function dreamfish_theme_fetchpage($page) {
		global $CONFIG;
		$body = "";
		switch ($page[0])
		{
			case "login":
				$body = elgg_view("account/forms/login");
				break;
			case "MemberDashboard":
				$body = '';
				$pages = search_for_object('DF_'.'MemberDashboard');
				if ($pages && sizeof($pages) > 0) {
					$body .= $pages[0]->description;
				}
					
	
				//clear existing widgets
				$area1widgets = get_widgets(page_owner(),'dashboard',1);
	
				foreach($area1widgets as $widget) {
					//TODO: figure out a way to clear out widgets for non-admin users. this works, but doesn't seem safe
					$res = delete_data("DELETE from {$CONFIG->dbprefix}entities where guid={$widget->get('guid')}");
				}
	
				$area1widgets = get_widgets(page_owner(),'dashboard',1);
	
				$area1widgets = get_widgets(page_owner(),'dashboard',1);
	
				$guid = $_SESSION['guid'];
				add_widget ( $guid, 'river_widget', 'dashboard', 1, 1 );
				add_widget ( $guid, 'a_users_groups', 'dashboard', 2, 1 );
				add_widget ( $guid, 'bookmarks', 'dashboard', 3, 1 );
				add_widget ( $guid, 'tasks', 'dashboard', 4, 1 );
	
				//display widgets
				$area1widgets = get_widgets(page_owner(),'dashboard',1);
	
				foreach($area1widgets as $widget) {
					$body .= elgg_view_entity($widget);
				}
				break;
			default:
				$pages = search_for_object('DF_'.$page[0]);
				if ($pages && sizeof($pages) > 0) {
					$body .= $pages[0]->description;
				} else
				{
					$body = $page[0] . ' does not exist';
				}
		}
		$content = elgg_view_layout('one_column', $body);
		echo page_draw(null, $content);
			
	}
	
	// Hook handlers
	
	/**
	 * This hook extends the register action with Dreamfish-specific registration validation
	 * 
	 * @param $hook
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 */
	function registration_hook($hook, $entity_type, $return_value, $params) {
		if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['_captcha'])) != $_SESSION['captcha']) {
			register_error("Invalid captcha");
			error_log("ACCOUNT: INVALID CAPTCHA");
			return false;
		}
		
		if (!empty($_REQUEST['spam'])) {
			register_error("that field wasn't supposed to be filled out!");
			error_log("ACCOUNT: SPAM FIELD FILLED OUT");
			return false;
		}
		
		$name = trim(get_input('name'));
		$pos = strpos($name, " ");
		if ($pos == false) {
			error_log("ACCOUNT: NAME FIELD DOES NOT CONTAIN SPACE --> IDENTIFIED AS BOT");
			return false;
		}

		return true;
	}
	
	function dreamfish_permissions_check($hook_name, $entity_type, $return_value, $parameters) {
		$entity = $parameters['entity'];
	
		if ($entity instanceof ElggObject) {
			$group = $entity->getContainerEntity();
			if ($group && $group instanceof ElggGroup)
			{
				$user = get_loggedin_user();
				if ($group->isMember($user))
				{
					return true;
				}
			}
	
		}
		return null;
	}
		
	// event handlers
	/**
	 * This event handler updates the newly created user with Dreamfish-specific properties; 
	 * in the current implementation, it adds newsletter regsitration metadata to the new user.
	 *
	 * @param $event='create'
	 * @param $object_type='user'
	 * @param $new_user
	 */
	function user_created_handler($event, $object_type, $new_user) {
		switch($event){
			case 'create':
				//see which newsletters have been selected
				$announce_key = 'df_announce';
				$new_proj_key  = 'df_new_projects';
				$df_announce_list = get_input($announce_key);
				$df_newproj_list = get_input($new_proj_key);
				$newsletters = array();

				if ($df_announce_list != '')
					array_push($newsletters , $announce_key);
				if ($df_newproj_list != '')
					array_push($newsletters, $new_proj_key);
				
				if (count($newsletters) > 0)
 					$new_user->set('newsletters', implode(',',$newsletters));
 				
				return true;
			default:
				return false;
		}

	}
	
	register_action('user/enable',true,$CONFIG->pluginspath . "dreamfish_theme/actions/enable.php");
	register_elgg_event_handler('init','system','dreamfish_theme_init');
	register_page_handler('page', 'dreamfish_theme_fetchpage');
	
?>
