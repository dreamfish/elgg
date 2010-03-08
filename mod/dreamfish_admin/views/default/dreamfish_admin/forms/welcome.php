<?php

gatekeeper();

$entity_type = 'object';
$subtype = 'df_custom_msg';
$owner_guid = 0;

$welcome_body_key = 'email:validate:success:body';
$welcome_subj_key = 'email:validate:success:subject';

$custom_messages = get_entities($entity_type, $subtype, $owner_guid);

$email_welcome_body = "";
$email_welcome_subj = "";
$s_guid = 0;
$b_guid = 0;

foreach ($custom_messages as $msg)
{
	if ($msg->title == $welcome_body_key) 
	{
		$email_welcome_body = $msg->description;	
		$b_guid = $msg->guid;
	}
	if ($msg->title == $welcome_subj_key) 
	{
		$email_welcome_subj = $msg->description;	
		$s_guid = $msg->guid;
	}
}

if ($email_welcome_body == "")
{
	$email_welcome_body = elgg_echo($welcome_body_key);
}
if ($email_welcome_subj == "")
{
	$email_welcome_subj = elgg_echo($welcome_subj_key);
}

$form_body = "<p>" . elgg_echo('dreamfish_admin:email_welcome_subj') . "<br />" . elgg_view('input/text' , array('internalname' => 'email_welcome_subj', 'class' => 'input-text', 'value' => $email_welcome_subj)) . "<br /><br />";

$form_body .= elgg_echo('dreamfish_admin:email_welcome_body') . "<br />" . elgg_view('input/longtext', array('internalname' => 'email_welcome', 'class' => 'general-textarea', 'value' => $email_welcome_body)) ."<br />";

$form_body .= elgg_view('input/submit', array('type' => 'submit', 'internalname' => 'submit',  'value' => elgg_echo('dreamfish_admin:save')))  . " </p>";

if ($s_guid != 0)
{
	$form_body .= elgg_view('input/hidden', array('internalname' => 's_guid', 'value' => $s_guid));
}

if ($b_guid != 0)
{
	$form_body .= elgg_view('input/hidden', array('internalname' => 'b_guid', 'value' => $b_guid));
}
	
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

