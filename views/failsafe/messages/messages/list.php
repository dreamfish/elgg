<?php

	/**
	 * Elgg list system messages
	 * Lists system messages
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An array of system messages
	 */

	if (!empty($vars['object']) && is_array($vars['object'])) {

?>

<style type="text/css">
.messages {
    border:1px solid #00cc00;
    background:#ccffcc;
    color:#000000;
    padding:3px 10px 3px 10px;
    margin:20px 0px 0px 0px;
    z-index: 9999;
    position:relative;
    width:95%;
}
</style>

	<div class="messages">

<?php

		
			foreach($vars['object'] as $message) {
				echo elgg_view('messages/messages/message',array('object' => $message));
			}

?>

	</div>
	
<?php

	}

?>