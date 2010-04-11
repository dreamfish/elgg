<?php

	/**
	 * Elgg external pages: add/edit
	 * 
	 * @package ElggExPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Make sure we're logged as admin
		admin_gatekeeper();

   // Make sure action is secure
        action_gatekeeper();

	// Get input data
		$contents = get_input('expagescontent', '', false);
		$type = get_input('content_type');
		$tags = get_input('expagestags');
		$previous_guid = get_input('expage_guid');

	// Cache to the session
		$_SESSION['expages_content'] = $contents;
		$_SESSION['expagestype'] = $type;
		$_SESSION['expagestags'] = $tags;
		
	// Convert string of tags into a preformatted array
		$tagarray = string_to_tag_array($tags);
		
	// Make sure the content exists
		if (empty($contents)) {
			register_error(elgg_echo("expages:blank"));
			forward("mod/expages/add.php");
			
	// Otherwise, save the new external page
		} else {
			
	//remove the old external page
		if(get_entity($previous_guid)){
			delete_entity($previous_guid);
		}	
		
		// Initialise a new ElggObject
			$expages = new ElggObject();
		// Tell the system what type of external page it is
			$expages->subtype = $type;
		// Set its owner to the current user
			$expages->owner_guid = $_SESSION['user']->getGUID();
		// For now, set its access to public
			$expages->access_id = 2;
		// Set its title and description appropriately
			$expages->title = $type;
			$expages->description = $contents;
		// Before we can set metadata, save
			if (!$expages->save()) {
				register_error(elgg_echo("expages:error"));
				forward("mod/expages/add.php");
			}
		// Now let's add tags. We can pass an array directly to the object property! Easy.
			if (is_array($tagarray)) {
				$expages->tags = $tagarray;
			}
						
		// Success message
			system_message(elgg_echo("expages:posted"));
		// add to river
		    add_to_river('river/expages/create','create',$_SESSION['user']->guid,$expages->guid);
		// Remove the cache
			unset($_SESSION['expages_content']); unset($_SESSION['expagestitle']); unset($_SESSION['expagestags']);
						
		
	// Forward back to the page
			forward("pg/expages/index.php?type={$type}");
				
		}
		
?>
