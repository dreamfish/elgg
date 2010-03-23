<?php

# Initialise common code
include("../../appg/settings.php");
$Configuration['SELF_URL'] = 'set_topbar.php';
include("../../appg/init_vanilla.php");

// use stripslashes because Vanilla seems to slash quotes

$_SESSION['elgg_topbar'] = stripslashes($_POST['elgg_topbar']);

?>