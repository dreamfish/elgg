<?php
	/**
	 * Elgg API default output
	 * This outputs the api in a human readable way.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	$result = $vars['result'];
	$export = $result->export();
	
?>
<div id="api_result">
	<table width="100%">
		<tr><td width="100" valign="top"><b>Status:</b></td> <td>
			<?php
				if ($result instanceof SuccessResult)
					echo "OK";
				else
					echo "**** ERROR ({$export->status}) ****";
			?>
		</td></tr>
		
		<?php if ($export->message!="") { ?>
		<tr><td width="100" valign="top"><b>Message:</b></td> <td><?php echo $export->message; ?></td></tr>
		<?php } ?>
		<?php if ($export->result) { ?>
		<tr><td width="100" valign="top"><b>Result:</b></td> <td><pre><?php print_r($export->result); ?></pre></td></tr>
		<?php } ?>
		
		
		<?php if ($export->pam) { ?>
		<tr><td width="100" valign="top"><b>PAM:</b></td> <td><pre><?php print_r($export->pam); ?></pre></td></tr>
		<?php } ?>
		
		<?php if ($export->runtime_errors) { ?>
		<tr><td width="100" valign="top"><b>Runtime:</b></td> <td><pre><?php print_r($export->runtime_errors); ?></pre></td></tr>
		<?php } ?>
	</table>
</div>