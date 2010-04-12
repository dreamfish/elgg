<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'thewire' => "The stream",
			'thewire:user' => "%s's stream",
			'thewire:posttitle' => "%s's notes in the stream: %s",
			'thewire:everyone' => "All stream posts",
			'thewire:replies' => 'Replies to me',
	
			'thewire:read' => "Stream posts",
			
			'thewire:strapline' => "%s",
	
			'thewire:add' => "Post to the stream",
		    'thewire:text' => "A note in the stream",
			'thewire:reply' => "Reply",
			'thewire:via' => "via",
			'thewire:wired' => "Posted to the stream",
			'thewire:charleft' => "characters left",
			'item:object:thewire' => "Stream posts",
			'thewire:notedeleted' => "note deleted",
			'thewire:doing' => "What are you doing? Tell everyone in the stream:",
			'thewire:newpost' => 'New stream post',
			'thewire:addpost' => 'Post to the stream',

	
        /**
	     * The wire river
	     **/
	        
	        //generic terms to use
	        'thewire:river:created' => "%s posted",
	        
	        //these get inserted into the river links to take the user to the entity
	        'thewire:river:create' => "in the stream.",
	        
	    /**
	     * Wire widget
	     **/
	     
	        'thewire:sitedesc' => 'This widget shows the latest site notes posted to the stream',
	        'thewire:yourdesc' => 'This widget shows your latest notes posted to the stream',
	        'thewire:friendsdesc' => 'This widget will show the latest from your friends in the stream',
	        'thewire:friends' => 'Your friends in the stream',
	        'thewire:num' => 'Number of items to display',
	        
	        
	
		/**
		 * Status messages
		 */
	
			'thewire:posted' => "Your message was successfully posted to the stream.",
			'thewire:deleted' => "Your note was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'thewire:blank' => "Sorry; you need to actually put something in the textbox before we can save it.",
			'thewire:notfound' => "Sorry; we could not find the specified note.",
			'thewire:notdeleted' => "Sorry; we could not delete this shout.",
	
	
		/**
		 * Settings
		 */
			'thewire:smsnumber' => "Your SMS number if different from your mobile number (mobile number must be set to public for the wire to be able to use it). All phone numbers must be in international format.",
			'thewire:channelsms' => "The number to send SMS messages to is <b>%s</b>",
			
	);
					
	add_translation("en",$english);

?>
