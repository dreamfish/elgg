<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
// Load Vanillaforum model
require_once(dirname(__FILE__) . "/models/model.php");

set_context("vanillaforum");

vanillaforum_add_to_river(get_input('type',''));
?>