<?php

    /**
	 * Elgg reply to a message form
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 *
	 * @uses $vars['entity'] This is the message being replied to
	 *
	 */
	 
?>

<form action="<?php echo $vars['url']; ?>action/messages/send" method="post" name="messageForm">

    <!-- populate the title space with the orginal message title, inserting re: before it -->						        
	<p><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' class="input-text" value='RE:<?php echo $vars['entity']->title; ?>' /></label></p>
	<p><label><?php echo elgg_echo("messages:message"); ?>: <br /><textarea name='message' value='' class="input-textarea" /></textarea></label></p>
		
	<p>
	    <?php
                
	        //pass across the guid of the message being replied to
    	    echo "<input type='hidden' name='reply' value='" . $vars['entity']->getGUID() . "' />";
    	    //pass along the owner of the message being replied to
    	    echo "<input type='hidden' name='send_to' value='BAAA" . $vars['entity']->fromId . "' />";
	
	    ?>
	    <input type="submit" class="submit_button" value="<?php echo elgg_echo("messages:fly"); ?>!" />
	</p>
	
</form>
	
	<?php
        //display the message you are replying to
		if (isset($vars['entity'])) {
    		
    		echo "<h3>" . elgg_echo("messages:replying") . "</h3>";
    		echo $vars['entity']->description;
    		
		}
    ?>