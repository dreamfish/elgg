<?php
	/**
	* Profile Manager
	* 
	* view to extend the user details
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
	
	$profile_user = $vars["entity"];
	$categorized_fields = profile_manager_get_categorized_fields($profile_user);
	$cats = $categorized_fields['categories'];
	$fields = $categorized_fields['fields'];
	
	if(count($cats) > 0){
		
		$profile_type_guid = $profile_user->custom_profile_type;
		
		$result .= "<div id='custom_fields_userdetails'>\n";
		// only show category headers if more than 1 category available
		if(count($cats) > 1){
			$show_header = true;
		} else {
			$show_header = false;
		}
		
		foreach($cats as $cat_guid => $cat){
			if($show_header){
				// make nice title
				if($cat_guid == 0){
					$title = elgg_echo("profile_manager:categories:list:default");
				} else {
					$title = $cat->getTitle();
				}
				
				$result .= "<h3><span class='accordion-icon'></span>" . $title . "</h3>\n";
			}
			
			$result .= "<div>\n";
			$even_odd = "even";
			
			foreach($fields[$cat_guid] as $field){
				$metadata_name = $field->metadata_name;
				
				if($metadata_name != "description"){
					// give correct class
					if($even_odd != "even"){
						$even_odd = "even";
					} else {
						$even_odd = "odd";
					}
					$result .= "<p class='" . $even_odd . "'>";
					
					// make nice title
					$title = $field->getTitle();
					
					// adjust output type
					if($field->output_as_tags == "yes"){
						$output_type = "tags";
					} else {
						$output_type = $field->metadata_type;
					}
					
					// build result
					$field_result = "<b>" . $title . "</b>:&nbsp;";
					$field_result .= elgg_view("output/" . $output_type, array("value" => $profile_user->$metadata_name));
					
					$result .=  $field_result;
					$result .= "</p>\n";
				}
			}
			$result .= "</div>\n";
		}
		$result .= "</div>\n";
		
?>
	<div id="custom_profile_fields_userdetails">
		<?php echo $result; ?>
	</div>
	
	<script type="text/javascript">
		$('#profile_info_column_middle > p:not(.profile_info_edit_buttons)').remove();
		
		var custom_userdetails = $('#custom_profile_fields_userdetails').html();
		$('#profile_info_column_middle').append(custom_userdetails);
	
		$('#custom_profile_fields_userdetails').remove();
	</script>
	
	<?php if(get_plugin_setting("display_categories", "profile_manager") == "accordion"){ ?>
	<script type="text/javascript" src="<?php echo $vars['url'];?>mod/profile_manager/vendors/jquery.ui/ui.accordion.packed.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url'];?>vendors/jquery/jquery.easing.1.3.packed.js"></script>
	<script type="text/javascript">
		$('#custom_fields_userdetails').accordion({
			header: 'h3',
			autoHeight: false
		});
	</script>
	<?php 
		}
	}
?>