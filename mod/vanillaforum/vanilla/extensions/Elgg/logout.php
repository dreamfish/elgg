<?php

# Initialise common code
include("../../appg/settings.php");
$Configuration['SELF_URL'] = 'logout.php';
include("../../appg/init_vanilla.php");

global $Context;

// log the person out

$Context->Session->End($Context->Authenticator);
exit();

?>