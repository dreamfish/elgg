<?php
	/**
	* Profile Manager
	* 
	* Profile Fields list view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 
	$count = get_entities("object", "custom_profile_field", 0, "", null, null, true);
    $fields = get_entities("object", "custom_profile_field", 0, "", $count, 0);
	
	$ordered = array();
	if($count > 0){	
		foreach($fields as $field){
			$ordered[$field->order] = $field;
		}
		
		ksort($ordered);
	}
		
	$fieldslist = elgg_view_entity_list($ordered, $count, 0, $count, false, false, false);
	$categorylist = elgg_view("profile_manager/categories/list");
	$profiletypelist = elgg_view("profile_manager/profile_types/list");
?>
<div id="custom_fields_ordering">
	<?php echo $fieldslist; ?>
</div>
<div id="custom_fields_lists">
	<div id="custom_fields_profile_type_list">
		<?php echo $profiletypelist; ?>	
	</div>
	<div id="custom_fields_category_list">
		<?php echo $categorylist; ?>	
	</div>
</div>
<div class="clearfloat"></div>