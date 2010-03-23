<?php
$options = array(elgg_echo('vanillaforum:settings:yes')=>'yes',
	elgg_echo('vanillaforum:settings:no')=>'no',
);

$body = '';

$vf_widget = get_plugin_setting('widget', 'vanillaforum');
if (!$vf_widget) {
	$vf_widget = 'yes';
}

$body .= elgg_echo('vanillaforum:settings:widget:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('internalname'=>'params[widget]','value'=>$vf_widget,'options'=>$options));

$body .= '<br />';

$vf_logout_page = get_plugin_setting('logout_page', 'vanillaforum');
if (!$vf_logout_page) {
	$vf_logout_page = 'yes';
}

$body .= elgg_echo('vanillaforum:settings:logout_page:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('internalname'=>'params[logout_page]','value'=>$vf_logout_page,'options'=>$options));


echo $body;
?>