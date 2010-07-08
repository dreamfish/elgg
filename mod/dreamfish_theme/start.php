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
		register_plugin_hook('action', 'register', 'registration_hook');
		register_elgg_event_handler('create','user','user_created_handler');	
	
		extend_view('profile/menu/links','usermenu');
		// Extend system CSS with our own styles
		extend_view('css','dreamfish_theme/css');
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
	 * This hook extends the register action with Dreamfish-specific 
	 * registration validation
	 * 
	 * @param $hook
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 */
	function registration_hook($hook, $entity_type, $return_value, $params) {
		error_log('DEBUG: executing registration_hook');
		
		if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['_captcha'])) != $_SESSION['captcha']) {
			error_log("ACCOUNT: INVALID CAPTCHA");
			register_error(elgg_echo('dreamfish_theme:register:error:captcha'));
			return false;
		}
		
		if (!empty($_REQUEST['spam'])) {
			error_log("ACCOUNT: SPAM FIELD FILLED OUT");
			register_error(elgg_echo('dreamfish_theme:register:error:spamfield'));
			return false;
		}
		
		$name = trim(get_input('name'));
		$pos = strpos($name, ' ');
		if ($pos == false) {
			error_log("ACCOUNT: NAME FIELD DOES NOT CONTAIN SPACE --> IDENTIFIED AS BOT");
			register_error(elgg_echo('dreamfish_theme:register:error:namespace'));
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
	 * This event handler updates the newly created user with 
	 * Dreamfish-specific properties; in the current implementation, 
	 * it adds newsletter regsitration metadata to the new user.
	 *
	 * @param $event='create'
	 * @param $object_type='user'
	 * @param $new_user
	 */
	function user_created_handler($event, $object_type, $new_user) {
		error_log('DEBUG: executing user created handler');
		switch($event){
			case 'create':
				error_log('DEBUG: Adding meta data and sending mail');
				//see which newsletters have been selected
				$announce_key = 'df_announce';
				$new_proj_key = 'df_new_projects';
				$df_announce_list = get_input($announce_key);
				$df_newproj_list = get_input($new_proj_key);
				$newsletters = array();

				if ($df_announce_list != '')
					array_push($newsletters , $announce_key);
				if ($df_newproj_list != '')
					array_push($newsletters, $new_proj_key);
				
				if (count($newsletters) > 0)
 					$new_user->set('newsletters', implode(',',$newsletters)); 					
 					
				return _send_user_registered_msg($new_user);
			default:
				return false;
		}

	}
	
	function _send_user_registered_msg($new_user)
	{
		$to = 'community-admin@dreamfish.com';
		$from = 'noreply@dreamfish.com';
		$sitename = 'Dreamfish';
		$header_eol = "\r\n";
		
		if ((isset($CONFIG->broken_mta)) && ($CONFIG->broken_mta))
			$header_eol = "\n"; // non-RFC 2822 mail headers for broken MTAs	
		$from_email = "\"$sitename\" <$from>";
		if (strcasecmp(substr(PHP_OS, 0 , 3), 'WIN')) 
			$from_email = "$from"; // diff format for broken Windows
			
		$headers = "From: $from_email{$header_eol}"
			. "Content-Type: text/plain; charset=UTF-8; format=flowed{$header_eol}"
    		. "MIME-Version: 1.0{$header_eol}"
    		. "Content-Transfer-Encoding: 8bit{$header_eol}";

    	if (is_callable('mb_encode_mimeheader')) {
			$subject = mb_encode_mimeheader($subject,"UTF-8", "B");
    	}
    	
    	$message = "A new user: $new_user->name ($new_user->email) has been registered.";
    	
		// Format message
    	$message = strip_tags($message); // Strip tags from message
    	$message = preg_replace("/(\r\n|\r)/", "\n", $message); // Convert to unix line endings in body
    	$message = preg_replace("/^From/", ">From", $message); // Change lines starting with From to >From  	
    		
		return mail($to, $subject, wordwrap($message), $headers);
		
	}
	
	register_action('user/enable', true, $CONFIG->pluginspath . "dreamfish_theme/actions/enable.php");
	register_elgg_event_handler('init', 'system', 'dreamfish_theme_init');
	register_page_handler('page', 'dreamfish_theme_fetchpage');
	
?>
