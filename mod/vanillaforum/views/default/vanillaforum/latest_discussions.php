<?php
if (isset($vars['limit'])) {
	$limit = $vars['limit'];
} else {
	$limit = 5;
}

$discussions = get_entities_from_metadata('post_type','vanilla_discussion',
	'object','vanillaforum_notify',0,$limit);

if ($discussions) {
	foreach ($discussions as $discussion) {
		$url = $vars['url'].'mod/vanillaforum/vanilla/comments.php?DiscussionID='.$discussion->did;
		$user = get_entity($discussion->owner_guid);
		$user_link = '<a href="'.$user->getURL().'">'.$user->name.'</a>';
		$discussion_link = '<a href="'.$url.'">'.$discussion->title.'</a>';

		$icon = '<a href="'.$user->getURL().'"><img src="'.$user->getIcon().'"></a>';
		$info = sprintf(elgg_echo('vanillaforum:widget_template'),$user_link,$discussion_link,$discussion->description);
		echo elgg_view_listing($icon, $info);
	}
}
?>