<?php 
	/**
	* Profile Manager
	* 
	* Profile Type Delete action
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
		
		if($entity->getSubtype() == CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE){	
			if($entity->delete()){
				$meta_name = "custom_profile_type";
				// remove corresponding profile type metadata from userobjects
				$entities_count = get_entities_from_metadata($meta_name,  $guid, "user", "", null,null,null,null,null,true);  
  				$entities = get_entities_from_metadata($meta_name,  $guid, "user", "", null,$entities_count);
				
  				foreach($entities as $entity){
  					// unset currently deleted profile type for user
  					unset($entity->$meta_name);
  				}
  				
				system_message(elgg_echo("profile_manager:action:profile_types:delete:succes"));
			} else {
				register_error(elgg_echo("profile_manager:action:profile_types:delete:error:delete"));
			}
		} else {
			register_error(elgg_echo("profile_manager:action:profile_types:delete:error:type"));
		}
	} else {
		register_error(elgg_echo("profile_manager:action:profile_types:delete:error:guid"));
	}
	
	forward($_SERVER["HTTP_REFERER"]);
?>