<?php 
	/**
	* Profile Manager
	* 
	* Category delete action
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	action_gatekeeper();
	admin_gatekeeper();
	
	$guid = get_input("guid");
	
	if(!empty($guid)){
		$entity = get_entity($guid);
		
		if($entity instanceof ProfileManagerCustomFieldCategory){
			$fields_count = get_entities_from_metadata("category_guid", $guid, "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, null, null, null, null, null, true);
			
			if($fields_count > 0){
				$fields = get_entities_from_metadata("category_guid", $guid, "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, null, $fields_count);
				
				foreach($fields as $field){
					unset($field->category_guid);
				}
			}
			
			if($entity->delete()){
				system_message(elgg_echo("profile_manager:action:category:delete:succes"));
			} else {
				register_error(elgg_echo("profile_manager:action:category:delete:error:delete"));
			}
		} else {
			register_error(elgg_echo("profile_manager:action:category:delete:error:type"));
		}
	} else {
		register_error(elgg_echo("profile_manager:action:category:delete:error:guid"));
	}
	
	forward($_SERVER["HTTP_REFERER"]);
?>