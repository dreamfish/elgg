<?php


	require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

		$body = "";
		$bleft = "";
		$bleft .= elgg_view("account/forms/login");
		$body .= $bleft;
		//$pages = get_entities_from_metadata_by_value('title', 'Home');
		$pages = search_for_object('Home');
		$body .= $pages[0]->description;
		set_context('main');
		global $autofeed;
		$autofeed = false;
				
				
		//$content = elgg_view_layout('two_column_left_sidebar', '', $body, $bleft);
		$content = elgg_view_layout('one_column', $body);
				
		echo page_draw(null, $content);


