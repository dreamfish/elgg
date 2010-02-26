<?php
	/**
	* Profile Manager
	* 
	* Export view
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$fieldtype = $vars['fieldtype'];
	$entities_count = get_entities("object", $fieldtype, null, null, null, null, true);
	$entities = get_entities("object", $fieldtype, null, null, $entities_count);
	
	$ts = time();
	$token = generate_action_token($ts);
?>
<div class='contentWrapper'>
<?php echo elgg_echo('profile_manager:export:description:' . $fieldtype);?>
<h3 class='settings'><?php echo elgg_echo('profile_manager:export:list:title');?></h3>
<?php 
	if($entities_count > 0){
		echo "<form action='" . $vars['url'] . "action/profile_manager/export' method='POST'>";
		echo "<input type='hidden' name='fieldtype' value='" . $fieldtype . "'></hidden>";
		echo "<input type='hidden' name='__elgg_ts' value='" . $ts . "'></hidden>";
		echo "<input type='hidden' name='__elgg_token' value='" . $token . "'></hidden>";
				
		echo "<table>";
		if($fieldtype == CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE){
			?>
			<tr>
				<td>
					<?php echo elgg_echo("guid");?>
				</td>
				<td>
					<input type='checkbox' name='export[guid]' value='guid'></input>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo elgg_echo("username");?>
				</td>
				<td>
					<input type='checkbox' name='export[username]' value='username'></input>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo elgg_echo("name");?>
				</td>
				<td>
					<input type='checkbox' name='export[name]' value='name'></input>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo elgg_echo("email");?>
				</td>
				<td>
					<input type='checkbox' name='export[email]' value='email'></input>
				</td>
			</tr>
			<?php 			
		}
		
		if($fieldtype == CUSTOM_PROFILE_FIELDS_GROUP_SUBTYPE){
			?>
			<tr>
				<td>
					<?php echo elgg_echo("guid");?>
				</td>
				<td>
					<input type='checkbox' name='export[guid]' value='guid'></input>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo elgg_echo("name");?>
				</td>
				<td>
					<input type='checkbox' name='export[name]' value='name'></input>
				</td>
			</tr>
			<?php 	
		}
		
		foreach($entities as $entity){
			?>
			<tr>
				<td>
					<?php echo $entity->metadata_name;?>
				</td>
				<td>
					<input type='checkbox' name='export[<?php echo $entity->metadata_name;?>]' value='<?php echo $entity->metadata_name;?>'></input>
				</td>
			</tr>
			<?php 
		}
		echo "</table>";
		// buttons
		echo elgg_view("input/submit", array("value" => elgg_echo("export")));
		echo "</form>";
	} else {
		echo elgg_echo("profile_manager:export:nofields");
	}
?>
</div>