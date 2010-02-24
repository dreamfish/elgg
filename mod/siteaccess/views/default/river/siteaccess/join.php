<?php

        $user = get_entity($vars['item']->object_guid); 

        $url = "<a href=\"{$user->getURL()}\">{$user->name}</a>";
        $string = sprintf(elgg_echo("siteaccess:river:join"),$url);

	echo $string;
?>

