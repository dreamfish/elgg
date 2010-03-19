<?php

admin_gatekeeper();

global $LANG_KEYS;
$newmessage_body = elgg_echo($LANG_KEYS->newmessage_body_key);
$newmessage_subj = elgg_echo($LANG_KEYS->newmessage_subj_key);

$form_body = "<p>" . elgg_echo('dreamfish_admin:email_newmessage_subj') . "<br />" . elgg_view('input/text' , array('internalname' => 'new_message_subj', 'class' => "input-text", 'value' => $newmessage_subj)) . "<br /><br />";

$form_body .= elgg_echo('dreamfish_admin:email_newmessage_body') . "<br />" . elgg_view('input/longtext', array('internalname' => 'new_message_body', 'class' => 'general-textarea', 'value' => $newmessage_body)) ."<br />";

$form_body .= elgg_view('input/submit', array('type' => 'submit', 'internalname' => 'submit',  'value' => elgg_echo('dreamfish_admin:save')))  . " </p>";

?>
<div id="dreamfish_new_msg">
<div class="contentWrapper">
<b>        <?php echo elgg_echo('dreamfish_admin:email_newmessage_label'); ?></b>
</div>
<div class="contentWrapper">
<font color="red">        <?php echo elgg_echo('dreamfish_admin:change_label_warning'); ?></font>
</div>
<div class="contentWrapper">
        <?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/dreamfish_admin/new_message", 'internalname' => 'newmsg_conf_form', 'body' => $form_body)) ?>
</div>
</div>

