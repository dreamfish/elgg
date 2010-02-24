<?php

    /**
	 * Elgg Pages welcome message
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
	 if($vars['entity']){
    	 
    	 foreach($vars['entity'] as $welcome){
    	 
    	    echo "<div class=\"contentWrapper pageswelcome\">" . $welcome->description . "</div>";
    	    
	    }
    	 
	 } else {

?>

<div class="contentWrapper pageswelcome"><p>Welcome to this Elgg pages plugin. This feature allows you to create pages on any topic and select who can view them and edit them.</p></div>
    
<?php
    }
?>