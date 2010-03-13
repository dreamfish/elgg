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
	$items = get_entities("user","",0,'',10000,false);
	echo "name,email,created,enabled, newsletters\r\n";
	foreach($items as $item)
	{
		$date = date('c', $item->getTimeCreated());
		$validated = $item->validated;
		if ($validated == '')
			$validated = 0;
		echo "\"{$item->name}\",\"{$item->email}\",\"{$date}\",{$validated},{$item->newsletters}\r\n";
	}
?>
