<?php 
	/**
	* Profile Manager
	* 
	* Profile Types add form 
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$formbody = "<table class='custom_fields_add_form_table'>\n";
	$formbody .= "<tr>\n";
	$formbody .= "<td class='custom_fields_add_form_table_left'>\n"; 
	$formbody .= elgg_echo('profile_manager:admin:metadata_name') . ":";
	$formbody .= elgg_view('input/text', array('internalname' => 'metadata_name'));
	
	$formbody .= elgg_echo('profile_manager:admin:metadata_label') . "*:";
	$formbody .= elgg_view('input/text', array('internalname' => 'metadata_label'));
	
	$formbody .= elgg_echo('profile_manager:admin:metadata_description') . "*:";
	$formbody .= elgg_view('input/plaintext', array('internalname' => 'metadata_description'));
	
	$formbody .= elgg_echo('profile_manager:admin:show_on_members') . "*:";
	$formbody .= elgg_view('input/pulldown', array('internalname' => 'show_on_members',
													"options_values" => array("no" => elgg_echo("option:no"),
																				"yes" => elgg_echo("option:yes"))));
			
	$formbody .= "</td>\n";
	$formbody .= "<td class='custom_fields_add_form_table_right'>\n"; 
	
	$category_count = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, null, null, true);
	
	if($category_count > 0){
		$categories = get_entities("object", CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE, null, null, $category_count);
		
		$options = array();
		
		foreach($categories as $cat){
			$title = $cat->getTitle();
			
			$options[$title] = $cat->guid;
		}
		
		$formbody .= elgg_view("input/checkboxes", array("internalname" => "categories", "options" => $options));
	} else {
		$formbody .= "&nbsp;";
	}
	
	$formbody .= "</td>\n";
	$formbody .= "</tr>\n";
	$formbody .= "</table>\n";
	
	$formbody .= elgg_view("input/hidden", array("internalname" => "guid"));
	$formbody .= elgg_view('input/submit', array('internalname' => elgg_echo('save'), 'value' => elgg_echo('save')));
	$formbody .= "&nbsp;";
	$formbody .= elgg_view('input/reset', array('internalname' => elgg_echo('cancel'), 
												'value' => elgg_echo('cancel'),
												'js' => "onClick='resetProfileTypeForm();'"));
	$formbody .= "&nbsp;";
	$formbody .= elgg_view('input/button', array('internalname' => elgg_echo('delete'),
												'class' => "submit_button custom_fields_profile_type_delete_button", 
												'value' => elgg_echo('delete'),
												'type' => "button",
												'js' => "onClick='deleteProfileType();'"));
	
	$form = elgg_view('input/form', array('body' => $formbody, 
										'action' => $vars['url'] . 'action/profile_manager/profile_types/add')
									);
	
?>
<div class="contentWrapper" id="custom_fields_profile_type_form">
	<h3 class="settings"><span class='custom_fields_more_info' id='more_info_profile_type'></span><?php echo elgg_echo("profile_manager:profile_types:add:link"); ?></h3>
	<?php echo $form; ?>
</div>