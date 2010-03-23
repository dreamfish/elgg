<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;
?>
     
jQuery.get("<?php echo $CONFIG->wwwroot; ?>mod/vanillaforum/topbar.php",save_topbar);