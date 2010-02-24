<?php

	/**
	 * Elgg reported content: delete action
	 * 
	 * @package ElggReportedCOntent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in and are admin
	    admin_gatekeeper();

	// Get input data
		$guid = (int) get_input('item');
		
	// Make sure we actually have permission to edit
		$reported = get_entity($guid);
		if ($reported->getSubtype() == "reported_content" && $reported->canEdit()) {
	
		// Delete it!
				if (!trigger_plugin_hook('reportedcontent:delete', $entity->type, array('entity'=>$entity), true)) {
	 				register_error(elgg_echo("reportedcontent:notdeleted"));
	 				forward("pg/reportedcontent/");
	 			}
				$rowsaffected = $reported->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("reportedcontent:deleted"));
				} else {
					register_error(elgg_echo("reportedcontent:notdeleted"));
				}
				
		// Forward back to the reported content page
				forward("pg/reportedcontent/");
		
		}
		
?>