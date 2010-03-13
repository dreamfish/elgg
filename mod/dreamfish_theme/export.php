<?php
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	$user = $_SESSION['user'];
	if(!isadminloggedin())
	{
		system_message('admin only');
		forward();
	}
	
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-disposition: attachment;filename=userexport.csv"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");
	$items = get_entities("user","",0,'',10000,false);
	foreach($items as $item)
	{
		$date = date('c', $item->getTimeCreated());
		echo "\"{$item->name}\",\"{$item->email}\",\"{$date}\", \"{$item->newsletters}\"\r\n";
	}
?>
