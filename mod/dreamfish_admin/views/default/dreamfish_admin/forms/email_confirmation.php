<?php

admin_gatekeeper();

global $LANG_KEYS;

$email_confirm_body = elgg_echo($LANG_KEYS->confirm_body_key);
$email_confirm_subj = elgg_echo($LANG_KEYS->confirm_subj_key);



$form_body = "<p>" . elgg_echo('dreamfish_admin:email_confirm_subj') . "<br />" . elgg_view('input/text' , array('internalname' => 'email_confirm_subj', 'class' => "input-text", 'value' => $email_confirm_subj)) . "<br /><br />";

$form_body .= elgg_echo('dreamfish_admin:email_confirm_body') . "<br />" . elgg_view('input/longtext', array('internalname' => 'email_confirm_body', 'class' => 'general-textarea', 'value' => $email_confirm_body)) ."<br />";

$form_body .= elgg_view('input/submit', array('type' => 'submit', 'internalname' => 'submit',  'value' => elgg_echo('dreamfish_admin:save')))  . " </p>";


?>
<div id="dreamfish_email_confirm">
<div class="contentWrapper">
<b>        <?php echo elgg_echo('dreamfish_admin:email_confirm_label'); ?></b>
</div>
<div class="contentWrapper">
<font color="red">        <?php echo elgg_echo('dreamfish_admin:change_label_warning'); ?></font>
</div>
<div class="contentWrapper">
        <?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/dreamfish_admin/email_confirm", 'internalname' => 'email_conf_form', 'body' => $form_body)) ?>
</div>
</div>

