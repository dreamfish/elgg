<?php
	/**
	 * Icon display
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

	$dgroup_guid = get_input('dgroup_guid');
	$dgroup = get_entity($dgroup_guid);
	
	$size = strtolower(get_input('size'));
	if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
		$size = "medium";
	
	$success = false;
	
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $dgroup->owner_guid;
	$filehandler->setFilename("dgroups/" . $dgroup->guid . $size . ".jpg");
	
	$success = false;
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
	
	if (!$success) {
		$contents = @file_get_contents($CONFIG->pluginspath . "dgroups/graphics/default{$size}.jpg");
	}
	
	header("Content-type: image/jpeg");
	header('Expires: ' . date('r',time() + 864000));
	header("Pragma: public");
	header("Cache-Control: public");
	header("Content-Length: " . strlen($contents));
	echo $contents;
?>