<?php

admin_gatekeeper();

global $LANG_KEYS;
$newfriend_body = elgg_echo($LANG_KEYS->newfriend_body_key);
$newfriend_subj = elgg_echo($LANG_KEYS->newfriend_subj_key);

$form_body = "<p>" . elgg_echo('dreamfish_admin:email_newfriend_subj') . "<br />" . elgg_view('input/text' , array('internalname' => 'new_friend_subj', 'class' => "input-text", 'value' => $newfriend_subj)) . "<br /><br />";

$form_body .= elgg_echo('dreamfish_admin:email_newfriend_body') . "<br />" . elgg_view('input/longtext', array('internalname' => 'new_friend_body', 'class' => 'general-textarea', 'value' => $newfriend_body)) ."<br />";

$form_body .= elgg_view('input/submit', array('type' => 'submit', 'internalname' => 'submit',  'value' => elgg_echo('dreamfish_admin:save')))  . " </p>";

?>
<div id="dreamfish_email_confirm">
<div class="contentWrapper">
<b>        <?php echo elgg_echo('dreamfish_admin:email_newfriend_label'); ?></b>
</div>
<div class="contentWrapper">
<font color="red">        <?php echo elgg_echo('dreamfish_admin:change_label_warning'); ?></font>
</div>
<div class="contentWrapper">
        <?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/dreamfish_admin/new_friend", 'internalname' => 'newfriend_conf_form', 'body' => $form_body)) ?>
</div>
</div>

