<?php
    $ts = time();
    $token = generate_action_token($ts);

    $email = $vars['email'];
    $form = '';
    $form .= "<p>" . elgg_echo('siteaccess:email:label:subject') . "</p>";
    $form .= elgg_view('input/text', array('internalname' => 'subject', 'value' => $email->title));
    $form .= "<p>" . elgg_echo('siteaccess:email:label:content') . "</p>";
    $form .= elgg_view('siteaccess/input/longtext', array('internalname' => "content", 'value' => $email->description));

    $form .= elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $email->guid));
    $form .= elgg_view('input/submit', array('internalname' => 'save', 'value' => elgg_echo('save')));
    $form .= " " . elgg_view('output/confirmlink', array('text' => elgg_echo('siteaccess:email:default'), 'href' => "{$vars['url']}action/siteaccess/email/delete?guid={$email->guid}&__elgg_token=$token&__elgg_ts=$ts"));
    echo $form;
?>
