<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	    
$default_css = elgg_view("vanillaforum/topbar_css");

header("Content-type: text/css", true);
header('Expires: ' . date('r',time() + 864000), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($default_css));
 
echo $default_css;
?>