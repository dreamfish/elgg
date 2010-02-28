<?php
	/**
	* Profile Manager
	* 
	* Action to import from default
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
	
	global $CONFIG;
	 
	action_gatekeeper();
	admin_gatekeeper();
	
	$type = get_input("type", "profile");
	
	if($type == "profile" || $type == "group"){
		$added = 0;	
		$defaults = array();
		
		$max_fields = get_entities("object", "custom_" . $type . "_field", $CONFIG->site_guid, null, null, null, true) + 1;
	
		if($type == "profile"){
			// Profile defaults
			$defaults = array (
					'description' => 'longtext',
					'briefdescription' => 'text',
					'location' => 'tags',
					'interests' => 'tags',
					'skills' => 'tags',
					'contactemail' => 'email',
					'phone' => 'text',
					'mobile' => 'text',
					'website' => 'url',
				);
		} elseif($type == "group"){
			// Group defaults
			$defaults = array(
				'description' => 'longtext',
				'briefdescription' => 'text',
				'interests' => 'tags',
				'website' => 'url',
			);
		}
		
		foreach($defaults as $metadata_name => $metadata_type){
		
			$count = get_entities_from_metadata("metadata_name", $metadata_name, "object", "custom_" . $type . "_field", $CONFIG->site_guid, "", null, null,null, true);
			
			if($count == 0){
				$field = new ElggObject();
						
				$field->owner_guid = $CONFIG->site_guid;
				$field->container_guid = $CONFIG->site_guid;
				$field->access_id = ACCESS_PUBLIC;
				$field->subtype = "custom_" . $type . "_field";
				$field->save();
				
				$field->metadata_name = $metadata_name;
				$field->metadata_type = $metadata_type;
				
				if($type == "profile"){
					$field->show_on_register = "no";
					$field->mandatory = "no";
					$field->user_editable = "yes";
				}
				$field->order = $max_fields;
				
				$field->save();
				
				$max_fields++;
				$added++;
			} 
		}
		
		if($added == 0){
			register_error(elgg_echo("profile_manager:actions:import:from_default:no_fields"));
		} else {
			system_message(sprintf(elgg_echo("profile_manager:actions:import:from_default:new_fields"), $added));
		}
	} else {
		register_error(elgg_echo("profile_manager:actions:import:from_default:error:wrong_type"));
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>