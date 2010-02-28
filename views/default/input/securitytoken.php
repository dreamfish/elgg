<?php
$ts = time();
$token = generate_action_token($ts);

echo elgg_view('input/hidden', array('internalname' => '__elgg_token', 'value' => $token));
echo elgg_view('input/hidden', array('internalname' => '__elgg_ts', 'value' => $ts));
