<?php 
	/**
	* Profile Manager
	* 
	* Output view of a datepicker
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	$dateformat = elgg_echo("profile_manager:datepicker:output:dateformat");
	
	if(date($dateformat, $vars['value']) != date($dateformat, 0)){
		echo strftime($dateformat, $vars['value']);
	} else {
		echo $vars['value'];
	}

?>