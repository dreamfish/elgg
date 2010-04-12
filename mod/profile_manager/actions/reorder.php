<?php 
	/**
	* Profile Manager
	* 
	* jQuery call to reorder the Custom Fields
	* 
	* @param ordering (array of guids)
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	action_gatekeeper();
	admin_gatekeeper();
	
	$ordering = get_input("custom_profile_field");
	
	if(!empty($ordering) && is_array($ordering)){
		foreach($ordering as $order => $guid){
			$entity = get_entity($guid);
			$entity->order = $order + 1;
		}	
	}
	
	exit();
?>