<?php 

	include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	

	$file_guid = (int) get_input('file_guid');
	$file = get_entity($file_guid);
	
	
	$readfile = new ElggFile();
	$readfile->owner_guid = 1; //$file->owner_guid;
	$readfile->setFilename($file->originalfilename);
	$filename = $readfile->getFilenameOnFilestore();
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	$mime = $file->getMimeType();
	$expires = 14 * 60*60*24;

	header("Content-Type: $mime");
	header("Content-Length: " . strlen($contents));
	header("Cache-Control: public", true);
	header("Pragma: public", true);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT', true);
	
	
	echo $contents;
	exit;

?>