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

<div class="contentWrapper pageswelcome">
Create a Page for any content you would put in a document (meeting notes, a plan, an article, portfolio, Journal, lesson, etc). A page can display text, link, images, videos and more. To share, you can choose who can edit and view the page. <b>To Create a Page</b>, click on "Create a Page" link on your left sidebar. (Want to learn more? Visit the <a href="http://www.dreamfish.com/pg/pages/view/3821/">help desk</a>)
</div>
    
<?php
    }
?>