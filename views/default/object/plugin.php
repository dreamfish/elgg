<?php
	/**
	 * Elgg plugin
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	$plugin = $vars['plugin'];
	$prefix = $vars['prefix']; // Do we want to show admin settings (default) or user settings
	
	$form_body = elgg_view("{$prefix}settings/{$plugin}/edit", $vars);
	$form_body .= "<p>" . elgg_view('input/hidden', array('internalname' => 'plugin', 'value' => $plugin)) . elgg_view('input/submit', array('value' => elgg_echo('save'))) . "</p>";
	

?>
<div>
	<?php echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/plugins/{$prefix}settings/save")); ?>
</div>