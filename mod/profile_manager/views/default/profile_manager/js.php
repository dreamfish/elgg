<?php
	/**
	* Profile Manager
	* 
	* JS (admin pages only, so no extend)
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 
	$ts = time();
	$token = generate_action_token($ts);
	$security_params = "__elgg_ts=" . $ts . "&__elgg_token=" . $token;
?>
<script type='text/javascript'>

	$(document).ready(function(){
		filterCustomFields(0);
		$('#custom_fields_ordering').sortable({
	  		update: function(event, ui) { 
	   			reorderCustomFields();			   		
	   		},
	   		opacity: 0.6,
	   		tolerance: 'pointer'
		});

		$('#custom_fields_category_list_custom').sortable({
			update: function(event, ui) { 
   				reorderCategories();			   		
   			},
			opacity: 0.6,
			containment: 'parent',
			tolerance: 'pointer'
		});

		$('#custom_fields_category_list .custom_fields_category').droppable({
			accept: ".search_listing",
			hoverClass: 'droppable-hover',
			tolerance: 'pointer',
			drop: function(event, ui) {
				var dropped_on = $(this).attr("id");  
				var dragged_field = $(ui.draggable);
				changeFieldCategory(dragged_field, dropped_on); 
			}
		});

		// enable/disable correct field type options 
		changeFieldType();
	});

	// General Functions
	
	function toggleForm(form_id){
		if($('#' + form_id + ' input[name="guid"]').val() > 0){
			$('#' + form_id + ' input[type="reset"]').click();
		} else {	
			$('#' + form_id).toggle();
		}		
	}
	
	// profile fields
	function resetProfileFieldsForm(){
		$('#custom_fields_form input[name="guid"]').val('');
		
		return true;
	}
	
	function editField(guid){
		$.getJSON("<?php echo $vars['url']; ?>action/profile_manager/get_field_data?<?php echo $security_params;?>&guid=" + guid, function(data){
			
			if(data.guid == guid){
				var form = $("#custom_fields_form");
				form.find('input[type="reset"]').click();
				$.each(data, function(name, value){
					if(value != null){
						$(form).find("[name='" + name + "']").val(value);
					}
				});
				form.show();
			} else {
				alert("<?php echo elgg_echo('profile_manager:actions:edit:error:unknown');?>")
			}
		});
	}
	
	function removeField(guid){
		if(confirm('<?php echo elgg_echo("profile_manager:actions:delete:confirm");?>')){
			$.post('<?php echo $vars['url']; ?>action/profile_manager/delete?<?php echo $security_params;?>&guid=' + guid, function(data){
				if(data == 'true'){
					$('#custom_profile_field_' + guid).hide('slow');
					$('#custom_profile_field_' + guid).remove();
					reorderCustomFields();
				} else {
					alert("<?php echo elgg_echo('profile_manager:actions:delete:error:unknown');?>");
				}
			});
		}	
	}
	
	function toggleOption(field, guid){
		$.post('<?php echo $vars['url']; ?>action/profile_manager/toggleOption?<?php echo $security_params;?>&guid=' + guid + '&field=' + field, function(data){
			if(data == 'true'){
				$("#" + field + "_" + guid).toggleClass("metadata_config_right_status_disabled");
				$("#" + field + "_" + guid).toggleClass("metadata_config_right_status_enabled");
			} else {
				alert("<?php echo elgg_echo('profile_manager:actions:toggle_option:error:unknown');?>");
			}
		});
	}
	
	function reorderCustomFields(){
		var strArray = $('#custom_fields_ordering').sortable('serialize');
		$.post('<?php echo $vars['url'];?>action/profile_manager/reorder?<?php echo $security_params;?>', strArray);
	}

	function changeFieldType(){
		selectedType = $("#custom_fields_form select[name='metadata_type']").val();
		$(".custom_fields_form_field_option").attr("disabled", "disabled");
		$(".field_option_enable_" + selectedType).attr("disabled", "");
	}

	// categories	
	function changeFieldCategory(field, category_guid){
		var field_guid = $(field).attr("id").replace("custom_profile_field_","");
		category_guid = category_guid.replace("custom_profile_field_category_","");

		$.post('<?php echo $vars['url']; ?>action/profile_manager/changeCategory?<?php echo $security_params;?>&guid=' + field_guid + '&category_guid=' + category_guid, function(data){
			if(data == 'true'){		
				if(category_guid == 0){
					category_guid = "";
				}				 
				$(field).attr("rel", category_guid);
				$(".custom_fields_category_selected a").click();
					
			} else {
				alert("<?php echo elgg_echo('profile_manager:actions:change_category:error:unknown');?>");
			}
		});
	}

	function filterCustomFields(category_guid){
		$("#custom_fields_ordering .search_listing").hide();
		$(".custom_fields_category").removeClass("custom_fields_category_selected");
		if(category_guid === 0){
			// show default
			$("#custom_fields_ordering .search_listing[rel='']").show();
			$("#custom_profile_field_category_0").addClass("custom_fields_category_selected");
		} else {
			if(category_guid === undefined){
				// show all
				$("#custom_fields_ordering .search_listing").show();
			} else {
				//show selected category
				$("#custom_fields_ordering .search_listing[rel='" + category_guid + "']").show();
				$("#custom_profile_field_category_" + category_guid).addClass("custom_fields_category_selected");
			}
		}		
	}
	
	function editCategory(guid, name, label, rels){
		$('#custom_fields_category_form input[name="guid"]').val(guid);
		$('#custom_fields_category_form input[name="metadata_name"]').val(name);
		$('#custom_fields_category_form input[name="metadata_label"]').val(label);

		var cats = rels.split(",");
		$('#custom_fields_category_form input[type="checkbox"]').val(cats);
		
		$('#custom_fields_category_form .custom_fields_category_delete_button').show();
		$('#custom_fields_category_form').show();
	}

	function reorderCategories(){
		var strArray = $('#custom_fields_category_list_custom').sortable('serialize');
		$.post('<?php echo $vars['url'];?>action/profile_manager/categories/reorder?<?php echo $security_params;?>', strArray);

	}

	function resetCategoryForm(){
		$('#custom_fields_category_form input[name="guid"]').val('');
		$('#custom_fields_category_form .custom_fields_category_delete_button').hide();
		
		return true;
	}

	function deleteCategory(){
		if(confirm("<?php echo elgg_echo("profile_manager:categories:delete:confirm"); ?>")){
			var guid = $('#custom_fields_category_form input[name="guid"]').val();
			document.location.href = "<?php echo $vars['url']; ?>action/profile_manager/categories/delete?<?php echo $security_params;?>&guid=" + guid;
		}
	}

	// Profile Types
	function resetProfileTypeForm(){
		$('#custom_fields_profile_type_form input[name="guid"]').val('');
		$('#custom_fields_profile_type_form .custom_fields_profile_type_delete_button').hide();
		
		return true;
	}

	function editProfileType(guid, name, label, show_on_members, rels){
		$('#custom_fields_profile_type_form input[name="guid"]').val(guid);
		$('#custom_fields_profile_type_form input[name="metadata_name"]').val(name);
		$('#custom_fields_profile_type_form input[name="metadata_label"]').val(label);
		$('#custom_fields_profile_type_form select[name="show_on_members"]').val(show_on_members);

		$.post("<?php echo $vars['url']; ?>action/profile_manager/profile_types/get_description?<?php echo $security_params;?>&guid=" + guid, function(data){
			$('#custom_fields_profile_type_form textarea[name="metadata_description"]').val(data);
		});
		
		var cats = rels.split(",");
		$('#custom_fields_profile_type_form input[type="checkbox"]').val(cats);
		
		$('#custom_fields_profile_type_form .custom_fields_profile_type_delete_button').show();
		$('#custom_fields_profile_type_form').show();
	}

	function deleteProfileType(){
		if(confirm("<?php echo elgg_echo("profile_manager:profile_types:delete:confirm"); ?>")){
			var guid = $('#custom_fields_profile_type_form input[name="guid"]').val();
			document.location.href = "<?php echo $vars['url']; ?>action/profile_manager/profile_types/delete?<?php echo $security_params;?>&guid=" + guid;
		}
	}

	function highlightCategories(elem, rels){
		$(elem).toggleClass("custom_fields_lists_green");
		var cats = rels.split(",");
		$.each(cats, function(){
			$("#custom_profile_field_category_" + this).toggleClass("custom_fields_lists_green");
		});
	}
</script>