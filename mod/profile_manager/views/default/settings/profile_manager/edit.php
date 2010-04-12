<?php
	/**
	* Profile Manager
	* 
	* Admin settings
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$plugins = get_plugin_list();
	// check plugin order
	if(array_search("profile", $plugins) > array_search("profile_manager", $plugins)){
		?>
		<h3 class='settings'><?php echo elgg_echo('profile_manager:admin:warning:profile');?></h3>
		<?php 
	}
?>

<table>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:profile_icon_on_register'); ?>
		</td>
		<td>
			<select name="params[profile_icon_on_register]">
				<option value="yes" <?php if ($vars['entity']->profile_icon_on_register == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->profile_icon_on_register != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:simple_access_control'); ?>
		</td>
		<td>
			<select name="params[simple_access_control]">
				<option value="yes" <?php if ($vars['entity']->simple_access_control == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->simple_access_control != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:hide_non_editables'); ?>
		</td>
		<td>
			<select name="params[hide_non_editables]">
				<option value="yes" <?php if ($vars['entity']->hide_non_editables == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->hide_non_editables != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:edit_profile_mode'); ?>
		</td>
		<td>
			<select name="params[edit_profile_mode]">
				<option value="list" <?php if ($vars['entity']->edit_profile_mode != 'tabbed') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:edit_profile_mode:list'); ?></option>
				<option value="tabbed" <?php if ($vars['entity']->edit_profile_mode == 'tabbed') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:edit_profile_mode:tabbed'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:show_full_profile_link'); ?>
		</td>
		<td>
			<select name="params[show_full_profile_link]">
				<option value="yes" <?php if ($vars['entity']->show_full_profile_link == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->show_full_profile_link != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:display_categories'); ?>
		</td>
		<td>
			<select name="params[display_categories]">
				<option value="plain" <?php if ($vars['entity']->display_categories != 'accordion') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:display_categories:option:plain'); ?></option>
				<option value="accordion" <?php if ($vars['entity']->display_categories == 'accordion') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:display_categories:option:accordion'); ?></option>
			</select>	
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:profile_type_selection'); ?>
		</td>
		<td>
			<select name="params[profile_type_selection]">
				<option value="user" <?php if ($vars['entity']->profile_type_selection != 'admin') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:profile_type_selection:option:user'); ?></option>
				<option value="admin" <?php if ($vars['entity']->profile_type_selection == 'admin') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('profile_manager:admin:profile_type_selection:option:admin'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:show_members_search'); ?>
		</td>
		<td>
			<select name="params[show_members_search]">
				<option value="yes" <?php if ($vars['entity']->show_members_search == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->show_members_search != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('profile_manager:admin:show_admin_stats'); ?>
		</td>
		<td>
			<select name="params[show_admin_stats]">
				<option value="yes" <?php if ($vars['entity']->show_admin_stats == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
				<option value="no" <?php if ($vars['entity']->show_admin_stats != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
			</select>
		</td>
	</tr>
</table>