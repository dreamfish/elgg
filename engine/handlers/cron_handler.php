<?php
	/**
	 * Elgg Cron handler.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
	define('externalpage',true);
	require_once("../start.php");
	global $CONFIG;
	
	// Get basic parameters
	$period = get_input('period');
	if (!$period) throw new CronException(sprintf(elgg_echo('CronException:unknownperiod'), $period));
	
	// Get a list of parameters
	$params = array();
	$params['time'] = time();
	
	foreach ($CONFIG->input as $k => $v)
		$params[$k] = $v;
	
	// Trigger hack
	$std_out = ""; // Data to return to
	$old_stdout = "";
	ob_start();
	
	$old_stdout = trigger_plugin_hook('cron', $period, $params, $old_stdout);
		
	$std_out = ob_get_clean();
	
	// Return event
	echo $std_out . $old_stdout;
?>