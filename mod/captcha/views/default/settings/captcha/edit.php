<?php
$body = '';

$body .= elgg_echo('reCAPTCHA Public Key');
$body .= '<br />';
$body .= elgg_view('input/text',array('internalname'=>'params[api_publickey]','value'=>get_plugin_setting('api_publickey', 'captcha')));

$body .= '<br /><br />';

$body .= elgg_echo('reCAPTCHA Private Key');
$body .= '<br />';
$body .= elgg_view('input/text',array('internalname'=>'params[api_privatekey]','value'=>get_plugin_setting('api_privatekey', 'captcha')));

$body .= '<br /><br />';
$body .= 'Visit <a href="http://recaptcha.net">reCAPTCHA</a> to obtain your own API Keys';
$body .= '<br /><br />';

echo $body;
?>