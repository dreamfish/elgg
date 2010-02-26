<?php
	/**
	* Profile Manager
	* 
	* Action to toggle profile field metadata
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

 	global $CONFIG;
 	
	action_gatekeeper();
	admin_gatekeeper();

	$allowed = array("mandatory", "show_on_register", "user_editable", "output_as_tags", "admin_only","simple_search", "advanced_search");
	
	$guid = get_input("guid");
	$field = get_input("field");
	
	if(!empty($guid) && in_array($field, $allowed)){
		$entity = get_entity($guid);
		if($entity->getSubtype() == CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE || $entity->getSubtype() == CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE){
			if($entity->$field == "yes"){
				$entity->$field = "no";
			} else {
				$entity->$field = "yes";
			}
			echo "true";		
		}
	}

	exit();
?>