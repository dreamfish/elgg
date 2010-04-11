<?php 
	/**
	* Profile Manager
	* 
	* jQuery call to remove a custom_profile_field or custom_group_field
	* 
	* @param guid (of the entity te remove)
	* @return true|null
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
		
		if(!empty($entity) && ($entity->getSubtype() == CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE || $entity->getSubtype() == CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE)){
			if($entity->delete()){
				echo "true";
			} 
		}
	}

	exit();
?>