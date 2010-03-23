<?php

	/**
	 * Elgg Vanilla forum integration
	 * 
	 * @package vanillaforum
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Curverider 2009
	 * @link http://elgg.org/
	 */
// Load plugin model
	require_once(dirname(__FILE__) . "/models/model.php");
	/**
	 * vanilla forum initialisation
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */

	function vanillaforum_init() {
		
		global $CONFIG;
		
		// Load the language files
		register_translations($CONFIG->pluginspath . "vanillaforum/languages/");
		
		register_plugin_hook('usersettings:save','user','vanillaforum_sync_settings');
		
		// add the Vanilla forum group tool option
		// Group forums commented out for now
		/*if (function_exists('add_group_tool_option')) {
			$default_to_vanilla_forum = get_plugin_setting('default_to_vanilla_forum', 'vanillaforum');
			if ($default_to_vanilla_forum == 'yes') {
				add_group_tool_option('vanilla_forum',elgg_echo('vanillaforum:enable_vanilla_forum'),true);
			} else {
				add_group_tool_option('vanilla_forum',elgg_echo('vanillaforum:enable_vanilla_forum'),false);
			}
		}*/
		
		extend_view('css','vanillaforum/css');
		
		add_menu(elgg_echo('vanillaforum:forum'), $CONFIG->wwwroot . "mod/vanillaforum/vanilla");
		
		if (get_plugin_setting('widget', 'vanillaforum') !== 'no') {
			//add a widget
			add_widget_type('vanillaforum',elgg_echo("vanillaforum:widget_title"),elgg_echo('vanillaforum:widget:description'));			
		}
	}
	
	function vanillaforum_pagesetup() {
		global $CONFIG;
		
		// Group forums commented out for now
		/*$page_owner = page_owner_entity();
		
		$context = get_context();
		// Group submenu option	
		if ($page_owner instanceof ElggGroup && $context == 'groups') {
			$vanilla_forum = $page_owner->vanilla_forum_enable;
			$default_to_vanilla_forum = get_plugin_setting('default_to_vanilla_forum', 'vanillaforum');
			if (($default_to_vanilla_forum == 'yes' && $vanilla_forum !== 'no') || ($vanilla_forum == 'yes')) {
    			$vanilla_url = $CONFIG->wwwroot."mod/vanillaforum/vanilla/?CategoryID=".$page_owner->getGUID();				
				add_submenu_item(elgg_echo("vanillaforum:forum"), $vanilla_url);
				if (!$page_owner->vanilla_forum_created) {
					// create the Vanilla forum
					vanillaforum_forum_create($page_owner);
					$page_owner->vanilla_forum_created = 1;
					$page_owner->save();					
				}
			}
		}*/
	}
	
	// this allows the vanillaforum notify to anonymously create objects owned by any user
	
	function vanillaforum_can_edit($hook_name, $entity_type, $return_value, $parameters) {
         
         if (get_context() == "vanillaforum") {
             return true;
         }
         return null;  
     }
     
     register_plugin_hook('container_permissions_check','object','vanillaforum_can_edit');
	
	// Make sure the vanillaforum event handlers are called
	register_elgg_event_handler('init','system','vanillaforum_init');
	register_elgg_event_handler('pagesetup','system','vanillaforum_pagesetup');
	// Group forums commented out for now
	//register_elgg_event_handler('delete','group','vanillaforum_forum_delete');
	//register_elgg_event_handler('update','group','vanillaforum_forum_update');
	
	global $CONFIG;
	register_action("logout",false,$CONFIG->pluginspath . "vanillaforum/actions/logout.php");
	
?>