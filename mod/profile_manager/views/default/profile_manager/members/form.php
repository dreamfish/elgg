<?php 
	$ts = time();
	$token = generate_action_token($ts);
	$url_security = "?__elgg_ts=" . $ts . "&__elgg_token=" . $token;
	
	$default_search_criteria = "<tr><td colspan='2'>";
	$default_search_criteria .= elgg_echo("name") . elgg_view("input/text", array("internalname" => "user_data_partial_search_criteria[name]"));
	$default_search_criteria .= "</td></tr><tr>";
	
	$profile_type_count = get_entities_from_metadata("show_on_members", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, null, null, null, true);
	if($profile_type_count > 0){
		$profile_types = get_entities_from_metadata("show_on_members", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, $profile_type_count);

		foreach($profile_types as $profile_type){
			// label
			$title = $profile_type->getTitle();
			
			$options[$title] = $profile_type->guid;
		}
				 
		$default_search_criteria .=  "<td>";
		$default_search_criteria .=  elgg_echo("profile_manager:profile_types:list:title") . "<br />";
		$default_search_criteria .=  elgg_view("input/checkboxes", array("internalname" => "profile_all_selector", "options" => array(elgg_echo("all")), "value" => elgg_echo("all") ,  "js" => "onchange='toggle_profile_type_selection($(this).parents(\"form\").attr(\"id\"));'"));
		$default_search_criteria .=  elgg_view("input/checkboxes", array("internalname" => "meta_data_array_search_criteria[custom_profile_type]", "options" => $options));
		$default_search_criteria .=  "</td>";
	} else {
		$default_search_criteria .=  "<td></td>";
	}
	
	$default_search_criteria .= "<td>" . elgg_echo("profile_manager:members:searchform:sorting"). "<br />";
	$default_search_criteria .= elgg_view("input/radio", array("internalname" => "sorting", "value" => "newest", "options" => array(elgg_echo("alphabetic") => "alphabetic", elgg_echo("newest") => "newest", elgg_echo("popular") => "popular", elgg_echo("online") => "online")));
	$default_search_criteria .= "</td></tr>";
	
	$simple_search_criteria = "";
	
	$simple_search_fields_count = get_entities_from_metadata("simple_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", null, null,null, true);
	if($simple_search_fields_count > 0){
		$simple_search_fields = get_entities_from_metadata("simple_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, $simple_search_fields_count);
		
		foreach($simple_search_fields as $field){
			if($field->admin_only != "yes" || isadminloggedin()){
				$ordered_simple_search_fields[$field->order] = $field;
			}
		}
		ksort($ordered_simple_search_fields);
		
		foreach($ordered_simple_search_fields as $field){
			$metadata_name = $field->metadata_name;
			$metadata_type = $field->metadata_type;
			if($metadata_type == "longtext" || $metadata_type == "plaintext"){
				$metadata_type = "text";
			}
			// make title
			$title = $field->getTitle();
			
			// get options
			$options = $field->getOptions();
	
			// type of search
			$search_type = get_search_type($metadata_type);
			
			// output field row
			$simple_search_criteria .= "<tr><td colspan='2'>";
			$simple_search_criteria .= $title . "<br />";
			
			if($search_type == "meta_data_between_search_criteria"){
				$simple_search_criteria .= elgg_echo("profile_manager:members:searchform:date:from") . " ";
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][FROM]"));
				$simple_search_criteria .= " " . elgg_echo("profile_manager:members:searchform:date:to") . " ";
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][TO]"));
			} else {
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
						"internalname" => $search_type . "[" . $metadata_name . "]",
						"options" => $options));
			}
			$simple_search_criteria .= "</td></tr>";
		}
	}
	
	$advanced_search_criteria = "";
	
	$advanced_search_fields_count = get_entities_from_metadata("advanced_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", null, null,null, true);
	if($advanced_search_fields_count > 0){
		$advanced_search_fields = get_entities_from_metadata("advanced_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, $advanced_search_fields_count);
		
		foreach($advanced_search_fields as $field){
			if($field->admin_only != "yes" || isadminloggedin()){
				$ordered_advanced_search_fields[$field->order] = $field;
			}
		}
		ksort($ordered_advanced_search_fields);
		
		foreach($ordered_advanced_search_fields as $field){
			$metadata_name = $field->metadata_name;
			$metadata_type = $field->metadata_type;
			if($metadata_type == "longtext" || $metadata_type == "plaintext"){
				$metadata_type = "text";
			}
			// make title
			$title = $field->getTitle();

			// get options
			$options = $field->getOptions();
	
			// type of search
			$search_type = get_search_type($metadata_type);
			
			// output field row
			$advanced_search_criteria .= "<tr><td colspan='2'>";
			$advanced_search_criteria .= $title . "<br />";
			
			if($search_type == "meta_data_between_search_criteria"){
				$advanced_search_criteria .= elgg_echo("profile_manager:members:searchform:date:from") . " ";
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][FROM]"));
				$advanced_search_criteria .= " " . elgg_echo("profile_manager:members:searchform:date:to") . " ";
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][TO]"));
			} else {
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
						"internalname" => $search_type . "[" . $metadata_name . "]",
						"options" => $options));
			}
			$advanced_search_criteria .= "</td></tr>";
		}
	}
	
	function get_search_type($metadata_type){
		$type = "meta_data_partial_search_criteria";
		if($metadata_type == "multiselect"){
			$type = "meta_data_array_search_criteria";
		} elseif($metadata_type == "pulldown" || $metadata_type == "radio") {
			$type = "meta_data_exact_search_criteria";
		} elseif($metadata_type == "datepicker" || $metadata_type == "calendar"){
			$type = "meta_data_between_search_criteria";
		} 
		return $type;
	}
	
?>
<style type="text/css">
	.hasDatepick {
		width: 100px !important;
	}
</style>
<script type="text/javascript">
	var formdata;
	function perform_members_search(formid){
		$("body").addClass("profile_manager_members_wait");

		formdata = $("#" + formid).serialize();
		
		$.post("<?php echo $vars['url'];?>action/profile_manager/members/search<?php echo $url_security;?>", formdata, function(data){
			$("#members_search_result").html(data);
			$("body").removeClass("profile_manager_members_wait");
		});
	}

	function navigate_members_search(offset){
		$("body").addClass("profile_manager_members_wait");
		$.post("<?php echo $vars['url'];?>action/profile_manager/members/search<?php echo $url_security;?>&offset=" + offset, formdata, function(data){
			$("#members_search_result").html(data);
			$("body").removeClass("profile_manager_members_wait");
		});
	}

	function toggle_profile_type_selection(formid){
		var status = "disabled";
		
		if(formid != undefined){
			formid = "#" + formid + " ";
		} else {
			var formid = "";
		}

		if(formid != ""){
			if($(formid + "input[name='profile_all_selector[]']").attr("checked") == false){
				status = "";
			}
		}

		$(formid + "input[name='meta_data_array_search_criteria[custom_profile_type][]']").attr("disabled", status);		
	}

	$(document).ready(function(){
		toggle_profile_type_selection();
		perform_members_search("simplesearch");
	});

</script>
<?php echo elgg_view_title(elgg_echo("profile_manager:members:searchform:title"));?>
<div id='profile_manager_members_search_form' class='contentWrapper'>

<h3 class='settings' onclick='$("#simplesearch").toggle();$("#advancedsearch").toggle();'><?php echo elgg_echo("profile_manager:members:searchform:simple:title");?></h3>
<form id="simplesearch" action="javascript:perform_members_search('simplesearch');" type="post">
<table width=100%>
	<?php 
		echo $default_search_criteria;
		echo $simple_search_criteria;
	?>	
</table>

<?php 
	echo elgg_view("input/submit", array("value" => elgg_echo("search")));
	echo " ";
	echo elgg_view("input/reset", array("value" => elgg_echo("reset")));
?>

</form>

<?php
	// advanced search 
	if(!empty($advanced_search_criteria)){
?>
<h3 class='settings' onclick='$("#simplesearch").toggle();$("#advancedsearch").toggle();'><?php echo elgg_echo("profile_manager:members:searchform:advanced:title");?></h3>
<form id="advancedsearch" style="display:none" action="javascript:perform_members_search('advancedsearch');" type="post">
<table width=100%>
	<?php 
		echo $default_search_criteria;
		echo $advanced_search_criteria;
	?>
</table>

<?php 
	echo elgg_view("input/submit", array("value" => elgg_echo("search")));
	echo " ";
	echo elgg_view("input/reset", array("value" => elgg_echo("reset")));
?>

</form>
<?php 
	}
?>

</div>

<div id="members_search_result"></div>
<div class="clearfloat"></div>