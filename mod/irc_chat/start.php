<?php
		function irc_chat_init() 
		{
		global $CONFIG;
		add_menu(elgg_echo('Chat'), $CONFIG->wwwroot . "mod/irc_chat/");
		}

	register_elgg_event_handler('init','system','irc_chat_init');

?>