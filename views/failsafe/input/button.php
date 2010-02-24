<?php
	/**
	 * Create a input button
	 * Use this view for forms rather than creating a submit/reset button tag in the wild as it provides
	 * extra security which help prevent CSRF attacks.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * @uses $vars['type'] Submit or reset, defaults to submit.
	 * @uses $vars['src'] Src of an image
	 * 
	 */

	global $CONFIG;
	
	$class = $vars['class'];
	if (!$class) $class = "submit_button";

	if (isset($vars['type'])) { $type = strtolower($vars['type']); } else { $type = 'submit'; }
	switch ($type)
	{
		case 'button' : $type='button'; break;
		case 'reset' : $type='reset'; break;
		case 'submit':
		default: $type = 'submit';
	}
	
	$value = htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');
	$name = $vars['internalname'];
	$src = $vars['src'];
	if (strpos($src,$CONFIG->wwwroot)===false) $src = ""; // blank src if trying to access an offsite image.
?>
<input type="<?php echo $type; ?>" class="<?php echo $type; ?>_button" <?php echo $vars['js']; ?> value="<?php echo $value; ?>" src="<?php echo $src; ?>" class="<?php echo $class; ?>" />