<?php

	if ($vars['full'] && $smallthumb = $vars['entity']->smallthumb) {
 
		echo "<p><a href=\"{$vars['url']}action/file/download?file_guid={$vars['entity']->getGUID()}\"><img src=\"{$vars['url']}mod/file/thumbnail.php?file_guid={$vars['entity']->getGUID()}&size=large\" border=\"0\" /></a></p>";
		
	}

?>