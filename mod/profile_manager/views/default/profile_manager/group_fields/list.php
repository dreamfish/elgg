<?php 
	/**
	* Profile Manager
	* 
	* Group Fields list view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$count = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, 0, "", null, null, true);
    $fields = get_entities("object", CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE, 0, "", $count, 0);
	
	$ordered = array();
	if($count > 0){	
		foreach($fields as $field){
			$ordered[$field->order] = $field;
		}
		
		ksort($ordered);
	}
	
	$list = elgg_view_entity_list($ordered, $count, 0, $count, false, false, false);
	
?>
<div id="custom_fields_ordering" class="custom_fields_ordering_group">
	<?php echo $list; ?>
</div>