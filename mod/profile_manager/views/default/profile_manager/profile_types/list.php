<?php
	/**
	* Profile Manager
	* 
	* Profile Types list view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 
	$profile_types_count = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, null, null, true);
	
	if($profile_types_count > 0){
		$list = list_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, $profile_types_count, false, false, false);
	} else {
		$list = elgg_echo("profile_manager:profile_types:list:no_types");
	}
	
?>
<div class="contentWrapper">
	<h3 class="settings"><span class='custom_fields_more_info' id='more_info_profile_type_list'></span><?php echo elgg_echo("profile_manager:profile_types:list:title"); ?></h3>
	<div id="custom_fields_profile_types_list_custom">	
		<?php echo $list; ?>
	</div>
</div>