<?php 
	/**
	* Profile Manager
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
	
	require_once(dirname(__FILE__) . "/lib/classes.php");

	define("CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE", "custom_profile_field_category");
	define("CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE", "custom_profile_type");
	define("CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE", "custom_profile_field");
	define("CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE", "custom_group_field");
	
	define("CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_CATEGORY_RELATIONSHIP", "custom_profile_type_category_relationship");

	/**
	 * initialization of plugin
	 * 
	 * @return unknown_type
	 */
	function profile_manager_init(){
		global $CONFIG;

		// Extend CSS
		extend_view("css", "profile_manager/css");
		extend_view("css", "members/css");
		extend_view("js/initialise_elgg", "profile_manager/global_js");
		
		// add custom profile fields to register page
		extend_view("account/forms/register", "profile_manager/register");
		
		// extend the user profile view
		extend_view("profile/userdetails", "profile_manager/profile/userdetails");
		
		// link to full profile
		if(get_plugin_setting("show_full_profile_link") == "yes"){
			extend_view("profile/menu/actions", "profile_manager/profile/userlinks");
		}
		
		// Extend the admin statistics
		if(get_plugin_setting("show_admin_stats") == "yes"){
			extend_view("admin/statistics", "profile_manager/admin_stats");
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('defaultprofile', 'profile_manager_edit_defaults_page_handler');
		
		// Register Page handler for Custom Profile Fields
		register_page_handler("profile_manager", "profile_manager_page_handler");
		
		// Register Page handler for Members listing
		if(get_plugin_setting("show_members_search") == "yes"){
			register_page_handler("members", "profile_manager_members_page_handler");
			add_menu(elgg_echo("profile_manager:members:menu"), $CONFIG->wwwroot . "pg/members");
		}
		
		// admin user add, registered here to overrule default action
		register_action("useradd", false, $CONFIG->pluginspath . "profile_manager/actions/admin/useradd.php", true);
		
		// Register all custom field types
		register_custom_field_types();
		
		// Run once function to configure this plugin
		run_function_once('profile_manager_run_once', 1265673600); // 2010-02-09
		
	}
	
	function profile_manager_run_once(){
		global $CONFIG; 
		
		// upgrade
		$profile_field_class_name = "ProfileManagerCustomProfileField";
		$group_field_class_name = "ProfileManagerCustomGroupField";
		$field_type_class_name = "ProfileManagerCustomProfileType";
		$field_category_class_name = "ProfileManagerCustomFieldCategory";
		
		if($id = get_subtype_id('object', ProfileManagerCustomProfileField::SUBTYPE)){
			update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes set class='$profile_field_class_name' WHERE id=$id");
		} else {
			add_subtype('object', ProfileManagerCustomProfileField::SUBTYPE, $profile_field_class_name);	
		}
		
		if($id = get_subtype_id('object', ProfileManagerCustomGroupField::SUBTYPE)){
			update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes set class='$group_field_class_name' WHERE id=$id");
		} else {
			add_subtype('object', ProfileManagerCustomGroupField::SUBTYPE, $group_field_class_name);	
		}
		
		if($id = get_subtype_id('object', ProfileManagerCustomProfileType::SUBTYPE)){
			update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes set class='$field_type_class_name' WHERE id=$id");
		} else {
			add_subtype('object', ProfileManagerCustomProfileType::SUBTYPE, $field_type_class_name);	
		}
		
		if($id = get_subtype_id('object', ProfileManagerCustomFieldCategory::SUBTYPE)){
			update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes set class='$field_category_class_name' WHERE id=$id");
		} else {
			add_subtype('object', ProfileManagerCustomFieldCategory::SUBTYPE, $field_category_class_name);	
		}
	}
	
	/**
	 * function to handle the 'old' replace profile fields url
	 * 
	 * @param $page
	 * @return unknown_type
	 */
	function profile_manager_edit_defaults_page_handler($page){
		global $CONFIG;
		
		// Forward to new form url
		if($page[0] == "edit"){
			forward($CONFIG->wwwroot . "pg/profile_manager/profile_fields");
		} 
	}
	
	/**
	 * function to handle the nice urls for Custom Profile Fields
	 * 
	 * @param $page
	 * @return unknown_type
	 */
	function profile_manager_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "group_fields":
				include("group_fields.php");
				break;
			case "profile_fields":
				include("profile_fields.php");
				break;
			case "full_profile":
				set_input("profile_guid", $page[1]);
				include("full_profile.php");
				break;
			case "export":
				set_input("fieldtype", $page[1]);
				include("export.php");
				break;
		}
	}
	
	function profile_manager_members_page_handler($page){
		include("members.php");
	}
	
	/**
	 * Function to add menu items to the pages
	 * 
	 * @return unknown_type
	 */
	function profile_manager_pagesetup(){
		global $CONFIG;
		
		if(get_context() == "admin" && isadminloggedin()){
			if(is_plugin_enabled("profile")){
				// Remake admin submenu
				$subA = &$CONFIG->submenu["a"];
				
				foreach($subA as $index => $item){
					if($item->name == elgg_echo("profile:edit:default")){
						unset($subA[$index]);
					}
				}
			
				add_submenu_item(elgg_echo("profile:edit:default"), $CONFIG->wwwroot . "pg/profile_manager/profile_fields", "b");
			}
			
			if(is_plugin_enabled("groups")){
				add_submenu_item(elgg_echo("profile_manager:group_fields"), $CONFIG->wwwroot . "pg/profile_manager/group_fields", "b");
			}
		}
		if(get_plugin_setting("show_members_search") == "yes" && (get_input("handler") == "search" || strpos($_SERVER["REQUEST_URI"], "/search/") === 0)){
			add_submenu_item(elgg_echo('profile_manager:members:submenu'), $CONFIG->wwwroot . "pg/members", "b");
		}
	}
	
	/**
	 * Registes all custom field types
	 */
	function register_custom_field_types(){
		$profileoptions = array();
		$profileoptions["show_on_register"] = true;
		$profileoptions["mandatory"] = true;
		$profileoptions["user_editable"] = true;
		$profileoptions["output_as_tags"] = true;
		$profileoptions["admin_only"] = true;
		$profileoptions["simple_search"] = true;
		$profileoptions["advanced_search"] = true;		

		$groupoptions = array();
		$groupoptions["output_as_tags"] = true;
		$groupoptions["admin_only"] = true;		
		
		$calendaroptions = $profileoptions;
		unset($calendaroptions["simple_search"]);
		unset($calendaroptions["advanced_search"]);

		// registering profile field types
		add_custom_field_type("custom_profile_field_types", 'text', elgg_echo('text'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'longtext', elgg_echo('longtext'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'tags', elgg_echo('tags'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'url', elgg_echo('url'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'email', elgg_echo('email'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'calendar', elgg_echo('calendar'), $calendaroptions);
		add_custom_field_type("custom_profile_field_types", 'datepicker', elgg_echo('profile_manager:admin:options:datepicker'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'pulldown', elgg_echo('profile_manager:admin:options:pulldown'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'radio', elgg_echo('profile_manager:admin:options:radio'), $profileoptions);
		add_custom_field_type("custom_profile_field_types", 'multiselect', elgg_echo('profile_manager:admin:options:multiselect'), $profileoptions);
		//add_custom_field_type("custom_profile_field_types", 'file', elgg_echo('profile_manager:admin:options:file'));

		// registering group field types		
		add_custom_field_type("custom_group_field_types", 'text', elgg_echo('text'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'longtext', elgg_echo('longtext'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'tags', elgg_echo('tags'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'url', elgg_echo('url'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'email', elgg_echo('email'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'calendar', elgg_echo('calendar'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'datepicker', elgg_echo('profile_manager:admin:options:datepicker'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'pulldown', elgg_echo('profile_manager:admin:options:pulldown'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'radio', elgg_echo('profile_manager:admin:options:radio'), $groupoptions);
		add_custom_field_type("custom_group_field_types", 'multiselect', elgg_echo('profile_manager:admin:options:multiselect'), $groupoptions);
	}
	
	/**
	 * Function to add a custom field type to a register
	 */
	function add_custom_field_type($register_name, $field_type, $field_display_name, $options){
		add_to_register($register_name, $field_type, $field_display_name, $options);
	}
	
	/**
	 * Hook to replace the profile fields
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $parameters
	 * @return unknown_type
	 */
	function profile_manager_profile_override($hook_name, $entity_type, $return_value, $parameters){
		global $CONFIG;
		
		$count = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", null, null, true);
	    
		if($count > 0){
			$result = array();
						
			// Get all the custom profile fields
			$entities = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", $count);
		    
		    // Make new result
		    foreach($entities as $entity){
		    
		    	if($entity->admin_only != "yes" || isadminloggedin()){
		    		$result[$entity->metadata_name] = $entity->metadata_type;
		    	}
	    		
	    		if(!empty($entity->metadata_label)){
		    		// Add a translation TODO: is this still needed?
					add_translation(get_current_language(), array("profile:" . $entity->metadata_name => $entity->metadata_label));
				} 	
		    }
	
			if(array_key_exists("description", $result)){
				// used for showing description in profile box (profile/userdetails)
				unset($CONFIG->profile_using_custom);
			} else {
				$CONFIG->profile_using_custom = true;
			}
			
			if(count($result)>0){
				$result["custom_profile_type"] = "non_editable";
			}
		}
		
		return $result;
	}
	
	/**
	 * function to replace group profile fields
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $parameters
	 * @return unknown_type
	 */
	function profile_manager_group_override($hook_name, $entity_type, $return_value, $parameters){
		global $CONFIG;
		$result = $return_value;
		
		$count = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, $CONFIG->site_guid, "", null, null, true);
		
		if($count > 0){
			$result = array();
			$ordered = array();
			
			// Get all custom group fields
			$group_fields = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, $CONFIG->site_guid, "", $count);
			
			// Order the group fields and filter some types out
			foreach($group_fields as $group_field){
				if($group_field->admin_only != "yes" || isadminloggedin()){
					$ordered[$group_field->order] = $group_field;
				}				
			}
			ksort($ordered);
			
			// build the correct list
			$result["name"] = "text";
			foreach($ordered as $group_field){
				$result[$group_field->metadata_name] = $group_field->metadata_type;
			}
		}
		
		return $result;
	}
	
	/**
	 * function to check if custom fields on register have been filled (if required)
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $parameters
	 * @return unknown_type
	 */
	function profile_manager_register_precheck($hook_name, $entity_type, $return_value, $parameters){
		// validate mandatory profile fields
		$count = get_entities_from_metadata_multi(array("show_on_register" => "yes", "mandatory" => "yes"), "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, null, null, null, null, true);
		$profile_icon = get_plugin_setting("profile_icon_on_register");
		
		if($count > 0 || $profile_icon == "yes"){
		    $entities = get_entities_from_metadata_multi(array("show_on_register" => "yes", "mandatory" => "yes"), "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, $count);
		    
		    $custom_profile_fields = array();
		    
		    foreach($_POST as $key => $value){
		    	if(strpos($key, "custom_profile_fields_") == 0){
		    		$key = substr($key, 22);
		    		$custom_profile_fields[$key] = $value;
		    	}
		    }
		    
		    foreach($entities as $entity){
		    	if($entity->admin_only != "yes"){
			    	$passed_value = $custom_profile_fields[$entity->metadata_name];
			    	
					if(empty($passed_value)){
						register_error(sprintf(elgg_echo("profile_manager:register_pre_check:missing"), $entity->getTitle()));
						forward_precheck_error($custom_profile_fields);					
					}
		    	}
		    }
		    
		    if($profile_icon == "yes"){
		    	$profile_icon = $_FILES["profile_icon"];
		    	
		    	$error = false;
		    	if(empty($profile_icon["name"])){
			    	register_error(sprintf(elgg_echo("profile_manager:register_pre_check:missing"), "profile_icon"));
			    	$error = true;
		    	} elseif($profile_icon["error"] != 0){
		    		register_error(elgg_echo("profile_manager:register_pre_check:profile_icon:error"));
		    		$error = true;
		    	} elseif(!in_array(strtolower(substr($profile_icon["name"], -3)), array("jpg","png","gif"))){
		    		register_error(elgg_echo("profile_manager:register_pre_check:profile_icon:nosupportedimage"));
		    		$error = true;
		    	}	
		    		   
		    	if($error){
		    		forward_precheck_error($custom_profile_fields);
		    	}
		    }
		}
	}
	
	/**
	 * function to forward back to registerpage on custom profile error
	 * 
	 * @param $custom_profile_fields
	 * @return unknown_type
	 */
	function forward_precheck_error($custom_profile_fields){
		$username = get_input('username');
		$email = get_input('email');
		$name = get_input('name');
		$friend_guid = (int) get_input('friend_guid',0);
		
		$qs = explode('?',$_SERVER['HTTP_REFERER']);
		$qs = $qs[0];
		$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
		
		foreach($custom_profile_fields as $key=>$value){
			if(is_array($value)){
				$value = implode(", ", $value);
			}
			$qspost .= "&custom_profile_fields[" . $key . "]=" . $value;
		}
		
		forward($qs . $qspost);
	}
	
	/**
	 * function to add custom profile fields to user on register
	 * 
	 * @param $event
	 * @param $object_type
	 * @param $object
	 * @return unknown_type
	 */
	function profile_manager_create_user($event, $object_type, $object){
		// add metadata
		$custom_profile_fields = array();
		    
	    foreach($_POST as $key=>$value){
	    	if(strpos($key, "custom_profile_fields_") == 0){
	    		$key = substr($key,22);
	    		$custom_profile_fields[$key] = $value;
	    	}
	    }
	    
		if(count($custom_profile_fields) > 0 ){
			foreach($custom_profile_fields as $key => $field){
				// use create_metadata to listen to ACCESS_DEFAULT
				create_metadata($object->guid, $key, $field, "", $object->guid, ACCESS_DEFAULT);
			}
		}
		
		if($profile_icon = $_FILES["profile_icon"]){
			add_profile_icon($object);
		}
	}
	
	/**
	 * function to upload a profile icon on register of a user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	function add_profile_icon($user){
		$topbar = get_resized_image_from_uploaded_file('profile_icon',16,16, true);
		$tiny = get_resized_image_from_uploaded_file('profile_icon',25,25, true);
		$small = get_resized_image_from_uploaded_file('profile_icon',40,40, true);
		$medium = get_resized_image_from_uploaded_file('profile_icon',100,100, true);
		$large = get_resized_image_from_uploaded_file('profile_icon',200,200);
		$master = get_resized_image_from_uploaded_file('profile_icon',550,550);
		
		if ($small !== false
			&& $medium !== false
			&& $large !== false
			&& $tiny !== false) {
		
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $user->getGUID();
			$filehandler->setFilename("profile/" . $user->username . "large.jpg");
			$filehandler->open("write");
			$filehandler->write($large);
			$filehandler->close();
			$filehandler->setFilename("profile/" . $user->username . "medium.jpg");
			$filehandler->open("write");
			$filehandler->write($medium);
			$filehandler->close();
			$filehandler->setFilename("profile/" . $user->username . "small.jpg");
			$filehandler->open("write");
			$filehandler->write($small);
			$filehandler->close();
			$filehandler->setFilename("profile/" . $user->username . "tiny.jpg");
			$filehandler->open("write");
			$filehandler->write($tiny);
			$filehandler->close();
			$filehandler->setFilename("profile/" . $user->username . "topbar.jpg");
			$filehandler->open("write");
			$filehandler->write($topbar);
			$filehandler->close();
			$filehandler->setFilename("profile/" . $user->username . "master.jpg");
			$filehandler->open("write");
            $filehandler->write($master);
			$filehandler->close();
			
			$user->icontime = time();
		}
	}
	
	/**
	 * returns an array containing the categories and the fields ordered by category and field order
	 */ 
	function profile_manager_get_categorized_fields($user = null, $edit = false, $register = false){
		$result = array();
		$profile_type = null;
		
		if($register == true){
			// failsafe for edit
			$edit = true;
		}
		
		if(!empty($user) && $user instanceof ElggUser){
			$profile_type_guid = $user->custom_profile_type;
			if(!empty($profile_type_guid)){
				$profile_type = get_entity($profile_type_guid);
				
				// check if profile type is a REAL profile type
				if(!empty($profile_type) && $profile_type instanceof ElggObject){
					if($profile_type->getSubtype() != CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE){
						$profile_type = null;
					}
				}
			}
		}
		
		$result["categories"] = array();
		$result["categories"][0] = array();
		$result["fields"] = array();
		$ordered_cats = array();
		
		$cat_count = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, null, null, true);
		
		// get ordered categories
		if($cat_count > 0){
			$cats = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, $cat_count);
			
			foreach($cats as $cat){
				$ordered_cats[$cat->order] = $cat;
			}
			ksort($ordered_cats);
		}
		
		// get filtered categories			
		$filtered_ordered_cats = array();
		// default category
		$filtered_ordered_cats[0] = array();
		
		if(!empty($ordered_cats)){
			foreach($ordered_cats as $key => $cat){
				if(!$edit){					
					$rel_count = get_entities_from_relationship(CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_CATEGORY_RELATIONSHIP, $cat->guid, true, "object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, null, null, true);
					if($rel_count == 0){
						$filtered_ordered_cats[$cat->guid] = array();
						$result["categories"][$cat->guid] = $cat;
					} elseif(!empty($profile_type) && check_entity_relationship($profile_type->guid, CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_CATEGORY_RELATIONSHIP, $cat->guid)){
						$filtered_ordered_cats[$cat->guid] = array();
						$result["categories"][$cat->guid] = $cat;
					}
				} else {
					$filtered_ordered_cats[$cat->guid] = array();
					$result["categories"][$cat->guid] = $cat;
				}
			}
		}
		
		$field_count = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, null, null, null, null, true);

		// adding fields to categories
		if($field_count > 0){
			$fields = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, null, null, $field_count);
			
			foreach($fields as $field){
				if(!($cat_guid = $field->category_guid)){
					$cat_guid = 0; // 0 is default
				}
				$admin_only = $field->admin_only;
				if($admin_only != "yes" || isadminloggedin()){
					if($edit){
						if(!$register || $field->show_on_register == "yes"){
							$filtered_ordered_cats[$cat_guid][$field->order] = $field;
						}
					} else {
						// only add if value exists
						$metadata_name = $field->metadata_name;

						if(!empty($user->$metadata_name) || $user->$metadata_name === 0){
							$filtered_ordered_cats[$cat_guid][$field->order] = $field;
						}
					}
				}
			}
		}
		
		// sorting fields and filtering empty categories
		foreach($filtered_ordered_cats as $cat_guid => $fields){
			if(!empty($fields)){
				ksort($fields);
				$result["fields"][$cat_guid] = $fields;
			} else {
				unset($result["categories"][$cat_guid]);
			} 
		}				
	
		return $result;
	}
	
	/**
	 * Function just now only ordered (name is prepped for future release)
	 */
	function profile_manager_get_categorized_group_fields($group = null){
		$result = array();
		$result["fields"] = array();
		
		$field_count = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, null, null, null, null, true);

		if($field_count > 0){
			$fields = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, null, null, $field_count);
			foreach($fields as $field){
				$admin_only = $field->admin_only;
				if($admin_only != "yes" || isadminloggedin()){
					$result["fields"][$field->order] = $field;
				}
			}
			ksort($result["fields"]);
		}
		return $result;			
	}
	
	// Initialization functions
	register_elgg_event_handler('init', 'system', 'profile_manager_init');
	register_elgg_event_handler('pagesetup', 'system', 'profile_manager_pagesetup');
	
	register_elgg_event_handler('create', 'user', 'profile_manager_create_user');
	
	register_plugin_hook('profile:fields', 'profile', 'profile_manager_profile_override');
	register_plugin_hook('profile:fields', 'group', 'profile_manager_group_override');
	
	register_plugin_hook('action', 'register', 'profile_manager_register_precheck');
	
	// actions
	register_action("profile_manager/new", false, $CONFIG->pluginspath . "profile_manager/actions/new.php", true);
	register_action("profile_manager/get_field_data", false, $CONFIG->pluginspath . "profile_manager/actions/get_field_data.php", true);
	register_action("profile_manager/reset", false, $CONFIG->pluginspath . "profile_manager/actions/reset.php", true);
	register_action("profile_manager/reorder", false, $CONFIG->pluginspath . "profile_manager/actions/reorder.php", true);
	register_action("profile_manager/delete", false, $CONFIG->pluginspath . "profile_manager/actions/delete.php", true);
	register_action("profile_manager/toggleOption", false, $CONFIG->pluginspath . "profile_manager/actions/toggleOption.php", true);
	register_action("profile_manager/changeCategory", false, $CONFIG->pluginspath . "profile_manager/actions/changeCategory.php", true);
	register_action("profile_manager/importFromCustom", false, $CONFIG->pluginspath . "profile_manager/actions/importFromCustom.php", true);
	register_action("profile_manager/importFromDefault", false, $CONFIG->pluginspath . "profile_manager/actions/importFromDefault.php", true);
	register_action("profile_manager/export", false, $CONFIG->pluginspath . "profile_manager/actions/export.php", true);
	register_action("profile_manager/configuration/backup", false, $CONFIG->pluginspath . "profile_manager/actions/configuration/backup.php", true);
	register_action("profile_manager/configuration/restore", false, $CONFIG->pluginspath . "profile_manager/actions/configuration/restore.php", true);
	
	register_action("profile_manager/categories/add", false, $CONFIG->pluginspath . "profile_manager/actions/categories/add.php", true);
	register_action("profile_manager/categories/reorder", false, $CONFIG->pluginspath . "profile_manager/actions/categories/reorder.php", true);
	register_action("profile_manager/categories/delete", false, $CONFIG->pluginspath . "profile_manager/actions/categories/delete.php", true);
	
	register_action("profile_manager/profile_types/add", false, $CONFIG->pluginspath . "profile_manager/actions/profile_types/add.php", true);
	register_action("profile_manager/profile_types/delete", false, $CONFIG->pluginspath . "profile_manager/actions/profile_types/delete.php", true);
	register_action("profile_manager/profile_types/get_description", false, $CONFIG->pluginspath . "profile_manager/actions/profile_types/get_description.php", true);
	
	// members
	register_action("profile_manager/members/search", true, $CONFIG->pluginspath . "profile_manager/actions/members/search.php");
	
?>