<?php

    /**
	 * Elgg send a message action page
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
	 // Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();
     
    // Get input data
		$title = get_input('title'); // message title
        $message_contents = get_input('message'); // the message
        $send_to = get_input('send_to'); // this is the user guid to whom the message is going to be sent
        $reply = get_input('reply',0); // this is the guid of the message replying to
        
        $user = get_user($send_to);
        if (!$user) {
        	register_error(elgg_echo("messages:user:nonexist"));
        	forward();
        }
        
    // Make sure the message field, send to field and title are not blank
		if (empty($message_contents) || empty($send_to) || empty($title)) {
			register_error(elgg_echo("messages:blank"));
			forward("mod/messages/send.php");
			
	// Otherwise, 'send' the message 
		} else {
    		
			$result = messages_send($title,$message_contents,$send_to,0,$reply);
			
	    // Save 'send' the message
			if (!$result) {
				register_error(elgg_echo("messages:error"));
				forward("mod/messages/send.php");
			} 
			
        // Success message
			system_message(elgg_echo("messages:posted"));
	
        // Forward to the users sentbox
			forward('pg/messages/' . $_SESSION['user']->username);	
    
        } // end of message check if statement
     
    
?>