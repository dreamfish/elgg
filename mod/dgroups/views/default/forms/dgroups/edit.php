<?php
	/**
	 * Elgg dgroups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

?>

<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/dgroups/edit" enctype="multipart/form-data" method="post">

	<p>
		<label><?php echo elgg_echo("dgroups:icon"); ?><br />
		<?php

			echo elgg_view("input/file",array('internalname' => 'icon'));
		
		?>
		</label>
	</p>
<?php

	//var_export($vars['profile']);
	if (is_array($vars['config']->dgroup) && sizeof($vars['config']->dgroup) > 0)
		foreach($vars['config']->dgroup as $shortname => $valtype) {
			
?>

	<p>
		<label>
			<?php echo elgg_echo("dgroups:{$shortname}") ?><br />
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $vars['entity']->$shortname,
															)); ?>
		</label>
	</p>

<?php
			
		}

?>

	<p>
		<label>
			<?php echo elgg_echo('dgroups:membership'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'membership','value' => $vars['entity']->membership, 'options' => array( ACCESS_PRIVATE => elgg_echo('dgroups:access:private'), ACCESS_PUBLIC => elgg_echo('dgroups:access:public')))); ?>
		</label>
	</p>
	
	
    <?php
		if (isset($vars['config']->dgroup_tool_options)) {
			foreach($vars['config']->dgroup_tool_options as $dgroup_option) {
				$dgroup_option_toggle_name = $dgroup_option->name."_enable";
				if ($dgroup_option->default_on) {
					$dgroup_option_default_value = 'yes';
				} else {
					$dgroup_option_default_value = 'no';
				}
?>	
    <p>
			<label>
				<?php echo $dgroup_option->label; ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => $dgroup_option_toggle_name,
									"value" => $vars['entity']->$dgroup_option_toggle_name ? $vars['entity']->$dgroup_option_toggle_name : $dgroup_option_default_value,
									'options' => array(
														elgg_echo('dgroups:yes') => 'yes',
														elgg_echo('dgroups:no') => 'no',
													   ),
													));
				?>
			</label>
	</p>
	<?php
		}
	}
	?>
	<p>
		<?php
			if ($vars['entity'])
			{ 
			?><input type="hidden" name="dgroup_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /><?php 
			}
		?>
		<input type="hidden" name="user_guid" value="<?php echo page_owner_entity()->guid; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
		
	</p>

</form>
</div>

<div class="contentWrapper">
<div id="delete_dgroup_option">
	<form action="<?php echo $vars['url'] . "action/dgroups/delete"; ?>">
		<?php
			if ($vars['entity'])
			{ 
				$warning = elgg_echo("dgroups:deletewarning");
			?>
			<input type="hidden" name="dgroup_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
			<input type="submit" name="delete" value="<?php echo elgg_echo('dgroups:delete'); ?>" onclick="javascript:return confirm('<?php echo $warning; ?>')"/><?php 
			}
		?>
	</form>
</div><div class="clearfloat"></div>
</div>



