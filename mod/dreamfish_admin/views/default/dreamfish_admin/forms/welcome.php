<?php

admin_gatekeeper();

global $LANG_KEYS;
$email_welcome_body = elgg_echo($LANG_KEYS->welcome_body_key);
$email_welcome_subj = elgg_echo($LANG_KEYS->welcome_subj_key);

$form_body = "<p>" . elgg_echo('dreamfish_admin:email_welcome_subj') . "<br />" . elgg_view('input/text' , array('internalname' => 'email_welcome_subj', 'class' => 'input-text', 'value' => $email_welcome_subj)) . "<br /><br />";

$form_body .= elgg_echo('dreamfish_admin:email_welcome_body') . "<br />" . elgg_view('input/longtext', array('internalname' => 'email_welcome', 'class' => 'general-textarea', 'value' => $email_welcome_body)) ."<br />";

$form_body .= elgg_view('input/submit', array('type' => 'submit', 'internalname' => 'submit',  'value' => elgg_echo('dreamfish_admin:save')))  . " </p>";


?>

<div id="dreamfish_welcome">
<div class="contentWrapper">
<b>        <?php echo elgg_echo('dreamfish_admin:email_welcome_label'); ?></b>
</div>
<div class="contentWrapper">
<font color="red">        <?php echo elgg_echo('dreamfish_admin:change_label_warning'); ?></font>
</div>
<div class="contentWrapper">
        <?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/dreamfish_admin/welcome", 'internalname' => 'email_welcome_form', 'body' => $form_body)) ?>
</div>
</div>

