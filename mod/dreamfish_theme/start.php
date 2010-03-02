<?php
	/**
	 * Elgg customindex plugin
	 * This plugin substitutes the frontpage with a custom one
	 * 
		* @package Customdash
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Boris Glumpler
	 * @copyright Boris Glumpler 2008
	 * @link /travel-junkie.com
	 */

	function customindex_init() {

		register_plugin_hook('index','system','new_index');		
		register_page_handler('dashboard','new_dashboard');
    register_plugin_hook('permissions_check', 'all', 'dreamfish_permissions_check');
    add_group_tool_option('blogposts','Enable Blog Posts',true);
    register_elgg_event_handler('pagesetup','system','df_pagesetup');    
   
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
		customindex_fetchpage(array('MemberDashboard'));
  }

	function new_index() {
		//if (!@include_once(dirname(dirname(__FILE__))) . "/customindex/index.php") return false;
		//return true;
		customindex_fetchpage(array('Home'));
		return true;
	}

	function customindex_fetchpage($page) {
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
	register_elgg_event_handler('init','system','customindex_init');
	register_page_handler('page', 'customindex_fetchpage');
?>
