<?php 
	/**
	* Profile Manager
	* 
	* Object view of a custom profile field type
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	if(get_context() != "search"){
		$object = $vars["entity"];
	
		// get title
		$title = $object->getTitle();
		
		$rels = "";
		
		$rel_count = get_entities_from_relationship(CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_CATEGORY_RELATIONSHIP, $object->guid, false, "object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, null, null, true);
		
		if($rel_count > 0){
			$cats = get_entities_from_relationship(CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_CATEGORY_RELATIONSHIP, $object->guid, false, "object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, $rel_count);
			
			$guids = array();
			foreach($cats as $cat){
				$guids[] = $cat->guid;
			}
			
			$rels = implode(",", $guids);
		}
	?>
	<div class="custom_profile_type" id="custom_profile_type_<?php echo $object->guid;?>" onmouseover="highlightCategories(this, '<?php echo $rels;?>');" onmouseout="highlightCategories(this, '<?php echo $rels;?>');">
		<div class="custom_profile_type_edit" onclick="editProfileType('<?php echo $object->guid;?>','<?php echo $object->metadata_name;?>','<?php echo $object->metadata_label;?>','<?php echo $object->show_on_members;?>', '<?php echo $rels; ?>');"></div>
		<?php echo $title; ?>
	</div>
	<?php
	} else {
		echo "&nbsp;";
	}
?>