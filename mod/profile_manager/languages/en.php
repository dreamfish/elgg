<?php 
	/**
	* Profile Manager
	* 
	* English language
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$english = array(
		'profile_manager' => "Profile Manager",
		'custom_profile_fields' => "Custom Profile Fields",
		'item:object:custom_profile_field' => 'Custom Profile Field',
		'item:object:custom_profile_field_category' => 'Custom Profile Field Category',
		'item:object:custom_profile_type' => 'Custom Profile Type',
		'item:object:custom_group_field' => 'Custom Group Field',
		
		// admin
		'profile_manager:admin:metadata_name' => 'Name',	
		'profile_manager:admin:metadata_label' => 'Label',
		'profile_manager:admin:metadata_hint' => 'Hint',
		'profile_manager:admin:metadata_description' => 'Description',
		'profile_manager:admin:metadata_label_translated' => 'Label (Translated)',
		'profile_manager:admin:metadata_label_untranslated' => 'Label (Untranslated)',
		'profile_manager:admin:metadata_options' => 'Options (comma separated)',
		'profile_manager:admin:options:datepicker' => 'Datepicker',
		'profile_manager:admin:options:pulldown' => 'Pulldown',
		'profile_manager:admin:options:radio' => 'Radio',
		'profile_manager:admin:options:multiselect' => 'MultiSelect',
		'profile_manager:admin:show_on_members' => "Show as filter on 'Members' page",
		
		'profile_manager:admin:additional_options' => 'Additional options',
		'profile_manager:admin:show_on_register' => 'Show on register form',	
		'profile_manager:admin:mandatory' => 'Mandatory',
		'profile_manager:admin:user_editable' => 'User can edit this field',
		'profile_manager:admin:output_as_tags' => 'Show on profile as tags',
		'profile_manager:admin:admin_only' => 'Admin only field',
		'profile_manager:admin:simple_search' => 'Show on simple search form',	
		'profile_manager:admin:advanced_search' => 'Show on advanced search form',		
		'profile_manager:admin:option_unavailable' => 'Option unavailable',
	
		'profile_manager:admin:profile_icon_on_register' => 'Add mandatory profile icon input field on register form',
		'profile_manager:admin:simple_access_control' => 'Show just one access control pulldown on edit profile form',
		
		'profile_manager:admin:hide_non_editables' => 'Hide the non editable fields from the Edit Profile form',
	
		'profile_manager:admin:edit_profile_mode' => "How to show the 'edit profile' screen",
		'profile_manager:admin:edit_profile_mode:list' => "List",
		'profile_manager:admin:edit_profile_mode:tabbed' => "Tabbed",
	
		'profile_manager:admin:show_full_profile_link' => 'Show a link to the full profile page',
	
		'profile_manager:admin:display_categories' => 'Select how the different categories are displayed on the profile',
		'profile_manager:admin:display_categories:option:plain' => 'Plain',
		'profile_manager:admin:display_categories:option:accordion' => 'Accordion',
	
		'profile_manager:admin:profile_type_selection' => 'Who can change the profile type?',
		'profile_manager:admin:profile_type_selection:option:user' => 'User',
		'profile_manager:admin:profile_type_selection:option:admin' => 'Admin only',
	
		'profile_manager:admin:show_admin_stats' => "Show admin statistics",
		'profile_manager:admin:show_members_search' => "Show the profile manager 'Members' search page",
	
		'profile_manager:admin:warning:profile' => "WARNING: This plugin should be below the Profile plugin",
	
		// profile field additionals description
		'profile_manager:admin:show_on_register:description' => "If you want this field to be on the register form.",	
		'profile_manager:admin:mandatory:description' => "If you want this field to be mandatory (only applies to the register form).",
		'profile_manager:admin:user_editable:description' => "If set to 'No' users can't edit this field (handy when data is managed in an external system).",
		'profile_manager:admin:output_as_tags:description' => "Data output will be handle as tags (only applies on user profile).",
		'profile_manager:admin:admin_only:description' => "Select 'Yes' if field is only available for admins.",
		'profile_manager:admin:simple_search:description' => "Select 'Yes' if field is searchable on the simple profile search form.",	
		'profile_manager:admin:advanced_search:description' => "Select 'Yes' if field is searchable on the advanced profile search form.",
		
		// non_editable
		'profile_manager:non_editable:info' => 'This field can not be edited',
	
		// profile user links
		'profile_manager:show_full_profile' => 'Full Profile',
	
		// datepicker
		'profile_manager:datepicker:output:dateformat' => '%a %d %b %Y', // For available notations see http://nl.php.net/manual/en/function.strftime.php
		'profile_manager:datepicker:input:localisation' => '', // change it to the available localized js files in custom_profile_fields/vendors/jquery.datepick.package-3.5.2 (e.g. jquery.datepick-nl.js), leave blank for default 
		'profile_manager:datepicker:input:dateformat' => '%m/%d/%Y', // Notation is based on strftime, but must result in output like http://keith-wood.name/datepick.html#format
		'profile_manager:datepicker:input:dateformat_js' => 'mm/dd/yy', // Notation is based on strftime, but must result in output like http://keith-wood.name/datepick.html#format
		
		// register form mandatory notice
		'profile_manager:register:mandatory' => "Items marked with a * are mandatory",	
	
		// register profile icon
		'profile_manager:register:profile_icon' => 'This site requires you to upload a profile icon',
		
		// simple access control
		'profile_manager:simple_access_control' => 'Select who can view your profile information',
	
		// register pre check
		'profile_manager:register_pre_check:missing' => 'The next field must be filled: %s',
		'profile_manager:register_pre_check:profile_icon:error' => 'Error uploading your profile icon (probably related to the file size)',
		'profile_manager:register_pre_check:profile_icon:nosupportedimage' => 'Uploaded profile icon is not the right type (jpg, gif, png)',
	
		// actions
		// new
		'profile_manager:actions:new:success' => 'Succesfully added new custom profile field',	
		'profile_manager:actions:new:error:metadata_name_missing' => 'No metadata name provided',	
		'profile_manager:actions:new:error:metadata_name_invalid' => 'Metadata name is an invalid name',	
		'profile_manager:actions:new:error:metadata_options' => 'You need to enter options when using this type',	
		'profile_manager:actions:new:error:unknown' => 'Unknown error occurred when saving a new custom profile field',
		'profile_manager:action:new:error:type' => 'Wrong profile field type (group or profile)',
		
		// edit
		'profile_manager:actions:edit:error:unknown' => 'Error fetching profile field data',
	
		//reset
		'profile_manager:actions:reset' => 'Reset',
		'profile_manager:actions:reset:description' => 'Removes all custom profile fields.',
		'profile_manager:actions:reset:confirm' => 'Are you sure you wish to reset all profile fields?',
		'profile_manager:actions:reset:error:unknown' => 'Unknown error occurred while resetting all profile fields',
		'profile_manager:actions:reset:error:wrong_type' => 'Wrong profile field type (group or profile)',
		'profile_manager:actions:reset:success' => 'Reset succesfull',
	
		//delete
		'profile_manager:actions:delete:confirm' => 'Are you sure you wish to delete this field?',
		'profile_manager:actions:delete:error:unknown' => 'Unknown error occurred while deleting',

		// toggle option
		'profile_manager:actions:toggle_option:error:unknown' => 'Unknown error occurred while changing the option',

		// actions
		'profile_manager:actions:title' => 'Actions',
	
		// import from custom
		'profile_manager:actions:import:from_custom' => 'Import custom fields',
		'profile_manager:actions:import:from_custom:description' => 'Imports previous defined (with default Elgg functionality) profile fields.',
		'profile_manager:actions:import:from_custom:confirm' => 'Are you sure you wish to import custom fields?',
		'profile_manager:actions:import:from_custom:no_fields' => 'No custom fields available for import',
		'profile_manager:actions:import:from_custom:new_fields' => 'Succesfully imported <b>%s</b> new fields',
	
		// import from default
		'profile_manager:actions:import:from_default' => 'Import default fields',
		'profile_manager:actions:import:from_default:description' => "Imports Elgg's default fields.",
				
		'profile_manager:actions:import:from_default:confirm' => 'Are you sure you wish to import default fields?',
		'profile_manager:actions:import:from_default:no_fields' => 'No default fields available for import',
		'profile_manager:actions:import:from_default:new_fields' => 'Succesfully imported <b>%s</b> new fields',
		'profile_manager:actions:import:from_default:error:wrong_type' => 'Wrong profile field type (group or profile)',
	
		// category to field
		'profile_manager:actions:change_category:error:unknown' => "An unknown error occured while changing the category",
	
		// add category
		'profile_manager:action:category:add:error:name' => "No name provided for the category",
		'profile_manager:action:category:add:error:object' => "Error while creating the category object",
		'profile_manager:action:category:add:error:save' => "Error while saving the category object",
		'profile_manager:action:category:add:succes' => "The category was created succefully",
	
		// delete category
		'profile_manager:action:category:delete:error:guid' => "No GUID provided",
		'profile_manager:action:category:delete:error:type' => "The provided GUID is not a custom profile field category",
		'profile_manager:action:category:delete:error:delete' => "An error occured while deleting the category",
		'profile_manager:action:category:delete:succes' => "The category was deleted succesfully",
	
		// add profile type
		'profile_manager:action:profile_types:add:error:name' => "No name provided for the Custom Profile Type",
		'profile_manager:action:profile_types:add:error:object' => "Error while creating the Custom Profile Type",
		'profile_manager:action:profile_types:add:error:save' => "Error while saving the Custom Profile Type",
		'profile_manager:action:profile_types:add:succes' => "The Custom profile Type was created succesfully",
		
		// delete profile type
		'profile_manager:action:profile_types:delete:error:guid' => "No GUID provided",
		'profile_manager:action:profile_types:delete:error:type' => "The provided GUID is not an Custom Profile Type",
		'profile_manager:action:profile_types:delete:error:delete' => "An unknown error occured while deleting the Custom Profile Type",
		'profile_manager:action:profile_types:delete:succes' => "The Custom Profile Type was deleted succesfully",
		
		// Custom Group Fields
		'profile_manager:group_fields' => "Replace group fields",
		'profile_manager:group_fields:title' => "Replace group profile fields",
		
		'profile_manager:group_fields:add:description' => "Here you can edit the fields that show on a group profile page",
		'profile_manager:group_fields:add:link' => "Add a new group profile field",
		
		'profile_manager:profile_fields:add:description' => "Here you can edit the fields a user can edit on his/her profile",
		'profile_manager:profile_fields:add:link' => "Add a new profile field",
	
		// Custom fields categories
		'profile_manager:categories:add:link' => "Add a new category",
		
		'profile_manager:categories:list:title' => "Categories",
		'profile_manager:categories:list:default' => "Default",
		'profile_manager:categories:list:view_all' => "View all fields",
		'profile_manager:categories:list:no_categories' => "No categories defined",
		
		'profile_manager:categories:delete:confirm' => "Are you sure you wish to delete this category?",
		
		// Custom Profile Types
		'profile_manager:profile_types:add:link' => "Add a new profile type",
		
		'profile_manager:profile_types:list:title' => "Profile Types",
		'profile_manager:profile_types:list:no_types' => "no profile types defined",
	
		'profile_manager:profile_types:delete:confirm' => "Are you sure you wish to delete this profile type?",
		
		// Export
		'profile_manager:actions:export' => "Export Profile Data",
		'profile_manager:actions:export:description' => "Export profile data to a csv file",
		'profile_manager:export:title' => "Export Profile Data",
		'profile_manager:export:description:custom_profile_field' => "This function will export all <b>user</b> metadata based on selected fields.",
		'profile_manager:export:description:custom_group_field' => "This function will export all <b>group</b> metadata based on selected fields.",
		'profile_manager:export:list:title' => "Select the fields which you want to be exported",
		'profile_manager:export:nofields' => "No custom profile fields available for export",
	
		// Configuration Backup and Restore
		'profile_manager:actions:configuration:backup' => "Backup Fields Configuration",
		'profile_manager:actions:configuration:backup:description' => "Backup the configuration of these fields (<b>categories and types are not backed up</b>)",
		'profile_manager:actions:configuration:restore' => "Restore Fields Configuration",
		'profile_manager:actions:configuration:restore:description' => "Restore a previously backed up configuration file (<b>you will loose relations between fields and categories</b>)",
		
		'profile_manager:actions:configuration:restore:upload' => "Restore",
	
		'profile_manager:actions:restore:success' => "Restore successfull",
		'profile_manager:actions:restore:error:deleting' => "Error while restoring: couldn't delete current fields",	
		'profile_manager:actions:restore:error:fieldtype' => "Error while restoring: fieldtypes do not match",
		'profile_manager:actions:restore:error:corrupt' => "Error while restoring: backup file seems to be corrupt or information is missing",
		'profile_manager:actions:restore:error:json' => "Error while restoring: invalid json file",
		'profile_manager:actions:restore:error:nofile' => "Error while restoring: no file uploaded",
	
		// Tooltips
		'profile_manager:tooltips:profile_field' => "
			<b>Profile Field</b><br />
			Here you can add a new profile field.<br /><br />
			If you leave the label empty, you can internationalize the profile field label (<i>profile:[name]</i>).<br /><br />
			Use the hint field to supply on input forms (register and profile/group edit) a hoverable icon with a field description.<br /><br />
			Options are only mandatory for fieldtypes <i>Pulldown, Radio and MultiSelect</i>.
		",
		'profile_manager:tooltips:profile_field_additional' => "
			<b>Show on register</b><br />
			If you want this field to be on the register form.<br /><br />
			
			<b>Mandatory</b><br />
			If you want this field to be mandatory (only applies to the register form).<br /><br />
			
			<b>User editable</b><br />
			If set to 'No' users can't edit this field (handy when data is managed in an external system).<br /><br />
			
			<b>Show as tags</b><br />
			Data output will be handle as tags (only applies on user profile).<br /><br />
			
			<b>Admin only field</b><br />
			Select 'Yes' if field is only available for admins.
		",
		'profile_manager:tooltips:category' => "
			<b>Category</b><br />
			Here you can add a new profile category.<br /><br />
			If you leave the label empty, you can internationalize the category label (<i>profile:categories:[name]</i>).<br /><br />
			
			If Profile Types are defined you can choose on which profile type this category applies. If no profile is specified, the category applies to all profile types (even undefined).
		",
		'profile_manager:tooltips:category_list' => "
			<b>Categories</b><br />
			Shows a list of all configured categories.<br /><br />
			
			<i>Default</i> is the category that applies to all profiles.<br /><br />
			
			Add fields to these categories by dropping them on the categories.<br /><br />
			
			Click the category label to filter the visible fields. Clicking view all fields shows all fields.<br /><br />
			
			You can also change the order of the categories by dragging them (<i>Default can't be dragged</i>. <br /><br />
			
			Click the edit icon to edit the category.
		",
		'profile_manager:tooltips:profile_type' => "
			<b>Profile Type</b><br />
			Here you can add a new profile type.<br /><br />
			If you leave the label empty, you can internationalize the profile type label (<i>profile:types:[name]</i>).<br /><br />
			Enter a description which users can see when selecting this profile type or leave it empty to internationalize (<i>profile:types:[name]:description</i>).<br /><br />
			You can add this profile type as filterable to the members search page<br /><br />
			
			If Categories are defined you can choose which categories apply to this profile type.
		",
		'profile_manager:tooltips:profile_type_list' => "
			<b>Profile Types</b><br />
			Shows a list of all configured profile types.<br /><br />
			Click the edit icon to edit the profile type.
		",
		'profile_manager:tooltips:actions' => "
			<b>Actions</b><br />
			Various actions related to these profile fields.
		",
		
		// Edit profile => profile type selector
		'profile_manager:profile:edit:custom_profile_type:label' => "Select your profile type",
		'profile_manager:profile:edit:custom_profile_type:description' => "Description of selected profile type",
		'profile_manager:profile:edit:custom_profile_type:default' => "Default",
	
		// Admin Stats
		'profile_manager:admin_stats:title'=> "Profile Manager Stats",
		'profile_manager:admin_stats:total'=> "Total user count",
		'profile_manager:admin_stats:profile_types'=> "Amount of users with profile type",
	
		// Members
		'profile_manager:members:menu' => "Members",
		'profile_manager:members:submenu' => "Members Search",
		'profile_manager:members:searchform:title' => "Search for Members",
		'profile_manager:members:searchform:simple:title' => "Simple Search",
		'profile_manager:members:searchform:advanced:title' => "Advanced Search",
		'profile_manager:members:searchform:sorting' => "Sorting",
		'profile_manager:members:searchform:date:from' => "from",
		'profile_manager:members:searchform:date:to' => "to",
		'profile_manager:members:searchresults:title' => "Search Results",
		'profile_manager:members:searchresults:query' => "QUERY",
		'profile_manager:members:searchresults:noresults' => "Your search didn't match any users",
		
	
		// Admin add user form
		'profile_manager:admin:adduser:notify' => "Notify user",
		'profile_manager:admin:adduser:use_default_access' => "Extra metadata created based on site default access level",
		'profile_manager:admin:adduser:extra_metadata' => "Add extra profile data",
	
	);
	
	add_translation("en", $english);
?>