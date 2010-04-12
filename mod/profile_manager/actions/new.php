<?php 
	/**
	* Profile Manager
	* 
	* Action to create/edit profile field
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	global $CONFIG;

	action_gatekeeper();
	admin_gatekeeper();

	$metadata_name = trim(get_input("metadata_name"));
	$metadata_label = trim(get_input("metadata_label"));
	$metadata_hint = trim(get_input("metadata_hint"));
	$metadata_type = get_input("metadata_type");
	$metadata_options = get_input("metadata_options");

	$show_on_register = get_input("show_on_register");
	$mandatory = get_input("mandatory");
	$user_editable = get_input("user_editable");
	$output_as_tags = get_input("output_as_tags");
	$admin_only = get_input("admin_only");
	$simple_search = get_input("simple_search");
	$advanced_search = get_input("advanced_search");
	
	$type = get_input("type", "profile");
	
	$guid = get_input("guid");
	if($guid){
		$current_field = get_entity($guid);
	}
	if($current_field && ($current_field->getSubtype() != CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE && $current_field->getSubtype() != CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE)){
		// wrong custom field type
		register_error(elgg_echo("profile_manager:action:new:error:type2"));
	} elseif($type != "profile" && $type != "group"){
		// wrong custom field type
		register_error(elgg_echo("profile_manager:action:new:error:type"));
	} elseif(empty($metadata_name)){
		// no name
		register_error(elgg_echo("profile_manager:actions:new:error:metadata_name_missing"));
	} elseif($metadata_name == "guid" || 
				$metadata_name == "title" || 
				$metadata_name == "access_id" || 
				$metadata_name == "owner_guid" || 
				$metadata_name == "container_guid" || 
				$metadata_name == "type" || 
				$metadata_name == "subtype" || 
				$metadata_name == "name" || 
				$metadata_name == "username" || 
				$metadata_name == "email" || 
				$metadata_name == "membership" || 
				$metadata_name == "group_acl" || 
				$metadata_name == "icon" || 
				$metadata_name == "site_guid" || 
				$metadata_name == "time_created" || 
				$metadata_name == "time_updated" || 
				$metadata_name == "enabled" || 
				$metadata_name == "tables_split" || 
				$metadata_name == "tables_loaded" || 
				$metadata_name == "password" || 
				$metadata_name == "salt" || 
				$metadata_name == "language" || 
				$metadata_name == "code" || 
				$metadata_name == "banned" || 
				$metadata_name == "custom_profile_type" || 
				!preg_match("/^[a-zA-Z0-9_]{1,}$/", $metadata_name)){
		// invalid name
		register_error(elgg_echo("profile_manager:actions:new:error:metadata_name_invalid"));
	} elseif(($metadata_type == "pulldown" || $metadata_type == "radio" || $metadata_type == "multiselect") && empty($metadata_options)){
		register_error(elgg_echo("profile_manager:actions:new:error:metadata_options"));
	} else {		 
		$existing = get_entities_from_metadata("metadata_name", $metadata_name, "object", "custom_" . $type . "_field", $CONFIG->site_guid, null,null,null,null,true);
		if(empty($current_field) && $existing > 0){
			register_error(elgg_echo("profile_manager:actions:new:error:metadata_name_invalid"));
		} else {
			$new_options = array();
			$options_error = false;
			if($metadata_type == "pulldown" || $metadata_type == "radio" || $metadata_type == "multiselect"){
				$temp_options = explode(",", $metadata_options);
				foreach($temp_options as $key => $option) {
					$trimmed_option = trim($option);
					if(!empty($trimmed_option)){
						$new_options[$key] = $trimmed_option;
					}
				}
				if(count($new_options) > 0 ){
					$new_options = implode(",", $new_options);
				} else {
					$options_error = true;
				}
			}
			
			if(!$options_error){
				$max_fields = get_entities("object", "custom_" . $type . "_field", $CONFIG->site_guid, null, null, null, true) + 1;

				if($current_field){
					$field = $current_field;
				} else {
					$field = new ElggObject();
						
					$field->owner_guid = $CONFIG->site_guid;
					$field->container_guid = $CONFIG->site_guid;
					$field->access_id = ACCESS_PUBLIC;
					$field->subtype = "custom_" . $type . "_field";
					$field->save();
				}	
				
				$field->metadata_name = $metadata_name;
				
				if(!empty($metadata_label)){
					$field->metadata_label = $metadata_label;
				}
				
				if(!empty($metadata_hint)){
					$field->metadata_hint = $metadata_hint;
				}
				
				$field->metadata_type = $metadata_type;
				if($metadata_type == "pulldown" || $metadata_type == "radio" || $metadata_type == "multiselect"){
					$field->metadata_options = $new_options;
				} elseif($current_field) {
					$field->clearMetaData("metadata_options");
				}
				
				if($type == "profile"){
					$field->show_on_register = $show_on_register;
					$field->mandatory = $mandatory;
					$field->user_editable = $user_editable;
					$field->simple_search = $simple_search;
					$field->advanced_search = $advanced_search;
					
				}
				$field->admin_only = $admin_only;
				$field->output_as_tags = $output_as_tags;

				if(empty($current_field)){
					$field->order = $max_fields;
				}
				
				if($field->save()){
					system_message(elgg_echo("profile_manager:actions:new:success"));
				} else {
					register_error(elgg_echo("profile_manager:actions:new:error:unknown"));
				}
			} else {
				register_error(elgg_echo("profile_manager:actions:new:error:metadata_options"));
			}
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>