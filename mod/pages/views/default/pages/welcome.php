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
Create a Page for any content you would put in a document (meeting notes, plan, article, portfolio, lesson, etc). A page can display text, link, images, videos and more.<br>
To share and collaborate on the Page, select Access to choose who can write or view the page. (Want to learn more? Visit the <a href="http://www.dreamfish.com/pg/pages/view/3821/">help desk</a>)<br>
To <b>create a new page</b>, click on the link, "New Page" on your left sidebar.

</div>
    
<?php
    }
?>