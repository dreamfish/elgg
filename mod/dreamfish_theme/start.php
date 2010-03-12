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

function unregister_elgg_event_handler($event, $object_type, $function) {
	global $CONFIG;
	foreach($CONFIG->events[$event][$object_type] as $key => $event_function) {
	if (strcmp($event_function, $function) == 0) {
			unset($CONFIG->events[$event][$object_type][$key]);
		}
	}
}

function unregister_plugin_hook($hook, $entity_type, $function) {
	global $CONFIG;
	foreach($CONFIG->hooks[$hook][$entity_type] as $key => $hook_function) {
		if (strcmp($hook_function, $function) == 0) {
			unset($CONFIG->hooks[$hook][$entity_type][$key]);
		}
	}
}

   /**
     * An event listener which will notify users based on certain events.
     *
     * @param unknown_type $event
     * @param unknown_type $object_type
     * @param unknown_type $object
     */
	function dfrelationship_notification_hook($event, $object_type, $object)
	{
		global $CONFIG;
		
		error_log("SENDING DFFRIEND MESSAGE");
		if (
			($object instanceof ElggRelationship) &&
			($event == 'create') &&
			($object_type == 'friend')
		)
		{
			$user_one = get_entity($object->guid_one);
			$user_two = get_entity($object->guid_two);
			
			// Notify target user
			return notify_user($object->guid_two, $object->guid_one, sprintf(elgg_echo('friend:newfriend:subject'), $user_one->name), 
				sprintf(elgg_echo("friend:newfriend:body"), $user_one->name, $CONFIG->site->url . "pg/profile/" . $user_one->username)
			); 
		}
	}
	function dreamfish_theme_init() {

	register_plugin_hook('index','system','new_index');		
	register_page_handler('dashboard','new_dashboard');
    register_plugin_hook('permissions_check', 'all', 'dreamfish_permissions_check');
    add_group_tool_option('blogposts','Enable Blog Posts',true);
    register_elgg_event_handler('pagesetup','system','df_pagesetup');    
	unregister_elgg_event_handler('create','friend','relationship_notification_hook');
	register_elgg_event_handler('create','friend','dfrelationship_notification_hook');
	
	// inserts dreamfish-specific form elements and JS on the registration page.
        extend_view('account/forms/register', 'register');

   
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
					$widget->delete();
				}
				
				//save widgets
				$widgettypes = get_widget_types();	
				foreach($widgettypes as $handler => $widget) {
					$guid = $_SESSION['guid'];					
					//add_widget ( $guid, $handler, 'dashboard', $i, 1 );
					//echo "<!--" . $handler . "-->";
					$i = $i + 1;				
				}
				
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
				//$body = elgg_view_layout('widgets',"","",'foo');
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
	register_elgg_event_handler('init','system','dreamfish_theme_init');
	register_page_handler('page', 'dreamfish_theme_fetchpage');
?>
