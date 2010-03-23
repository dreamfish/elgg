<?php
$vanillaforum_url = $vars['url'].'mod/vanillaforum/vanilla/';

$vanillaforum_logout_url = $vanillaforum_url.'extensions/Elgg/logout.php';
$iframe = '<IFRAME id="logout_iframe" SRC="'.$vanillaforum_logout_url.'" WIDTH="0" HEIGHT="0" FRAMEBORDER="0"></IFRAME>';

echo $iframe;

?>