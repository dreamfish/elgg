<?php
	/**
	* Profile Manager
	* 
	* Category list view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 
	$categories_count = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, null, null, true);
	
	if($categories_count > 0){
		$categories = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, $categories_count);
		
		$ordered = array();
		foreach($categories as $cat){
			$ordered[$cat->order] = $cat;
		}
		
		ksort($ordered);
		
		$list = elgg_view_entity_list($ordered, $categories_count, 0, $categories_count, false, false, false);
	} else {
		$list = elgg_echo("profile_manager:categories:list:no_categories");
	}
	
?>
<div class="contentWrapper">
	<h3 class="settings"><span class='custom_fields_more_info' id='more_info_category_list'></span><?php echo elgg_echo("profile_manager:categories:list:title"); ?></h3>
	<div id="custom_profile_field_category_0" class="custom_fields_category"><a href="javascript:void(0);" onclick="filterCustomFields(0)"><?php echo elgg_echo("profile_manager:categories:list:default"); ?></a></div>
	<div id="custom_fields_category_list_custom">	
		<?php echo $list; ?>
	</div>
	<center><a href="javascript:void(0);" onclick="filterCustomFields()"><?php echo elgg_echo("profile_manager:categories:list:view_all"); ?></a></center>
</div>