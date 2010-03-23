<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$user = get_user_by_username(get_input('username',''));
if ($user) {
	echo $user->getIcon();
} else {
	echo '';
}
?>