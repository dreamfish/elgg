<?php
	/**
	* Profile Manager
	* 
	* Admin stats view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$total_users = get_entities("user", "", null, null, null, null, true);

	$profile_types_count = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, null, null, true);
	$profile_entities = get_entities("object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, $profile_types_count);
	$profile_listing = "";
	foreach($profile_entities as $profile_type){
		$count = get_entities_from_metadata("custom_profile_type", $profile_type->guid, "user", "", null, null, null, null, null, true);
		$profile_listing .= "<b>" . $profile_type->metadata_name . "</b>: " . $count . "<br />";
	}

?>
<div class='contentWrapper'>
	<h3 class='settings'><?php echo elgg_echo("profile_manager:admin_stats:title");?></h3>
	
	<?php echo elgg_echo("profile_manager:admin_stats:total");?>: <?php echo $total_users;?><br /><br />
	<?php echo elgg_echo("profile_manager:admin_stats:profile_types");?>:<br />
	<?php echo $profile_listing;?>
</div>