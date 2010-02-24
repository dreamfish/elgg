<?php
	/**
	 * User validation plugin.
	 * Edit plugin settings
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

?>	 
<p>
<br />
<h3><?php echo elgg_echo('uservalidation:method'); ?>: </h3>
<?php

		$validationMethod = ($vars['entity']->validationMethod ? $vars['entity']->validationMethod : 'bymail');
		$myOptions = array(
			'internalname' => 'params[validationMethod]', 
			'value' => $validationMethod,
			'options_values' => array(
				'none' => elgg_echo('uservalidation:method:none'),
				'bymail' => elgg_echo('uservalidation:method:bymail'),
				'byadmin' => elgg_echo('uservalidation:method:byadmin')
			)
		);
		echo elgg_view('input/pulldown', $myOptions);
		
?>
<br />&nbsp;<br />
<h3><?php echo elgg_echo('uservalidation:adminmail'); ?>: </h3>
<?php

		$sendAdminMail = ($vars['entity']->sendAdminMail ? $vars['entity']->sendAdminMail : 'no');
		$myOptions = array(
			'internalname' => 'params[sendAdminMail]', 
			'value' => $sendAdminMail,
			'options_values' => array(
				'no' => elgg_echo('option:no'),
				'every' => elgg_echo('uservalidation:adminmail:every'),
				'adminonly' => elgg_echo('uservalidation:adminmail:adminonly')
			)
		);
		echo elgg_view('input/pulldown', $myOptions);
		
?>
<br />&nbsp;<br />
<h3><?php echo elgg_echo('uservalidation:autodelete'); ?>: </h3>
<?php

		$autoDeleteDays = ($vars['entity']->autoDeleteDays ? $vars['entity']->autoDeleteDays : 'no');
		$myOptions = array(
			'internalname' => 'params[autoDeleteDays]', 
			'value' => $autoDeleteDays,
			'options_values' => array(
				'0' => elgg_echo('uservalidation:autodelete:no'),
				'7' => '7',
				'14' => '14',
				'21' => '21',
				'28' => '28'
			)
		);
		echo elgg_view('input/pulldown', $myOptions);
		
?>
<br />&nbsp;<br />
</p>
