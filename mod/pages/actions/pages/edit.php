<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	set_context('pages');

	// Get group fields
	$input = array();
	foreach($CONFIG->pages as $shortname => $valuetype) {
		$input[$shortname] = get_input($shortname);
		if ($valuetype == 'tags')
			$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
	
	// Get parent
	$parent_guid = (int)get_input('parent_guid', 0);
	
	// New or old?
	$page = NULL;
	$pages_guid = (int)get_input('pages_guid');
	if ($pages_guid)
	{
		$page = get_entity($pages_guid);
		if (!$page->canEdit())
			$page = NULL; // if we can't edit it, go no further.
	}
	else
	{
		$page = new ElggObject();
		if (!$parent_guid)
			$page->subtype = 'page_top';
		else
			$page->subtype = 'page';
			
		// New instance, so set container_guid
		$container_guid = get_input('container_guid', $_SESSION['user']->getGUID());
		$page->container_guid = $container_guid;
	}
	
	// Have we got it? Can we edit it?
	if ($page instanceof ElggObject)
	{
		// Yes we have, and yes we can.
		
		// Save fields - note we always save latest description as both description and annotation
		if (sizeof($input) > 0)
		{
			foreach($input as $shortname => $value) {
				if ((!$pages_guid) || (($pages_guid) && ($shortname != 'title')))
					$page->$shortname = $value;
			}
		}
		
	
		// Validate create
		if (!$page->title)
		{
			register_error(elgg_echo("pages:notitle"));
			
			forward($_SERVER['HTTP_REFERER']);
			exit;
		}
	$container = get_entity($page->container_guid);
	if (strpos($page->title, 'DF_')==0 or isadmin() or ($container instanceof ElggGroup and $container->name == 'Community Guides')) {
		$page->description = get_input('description', '', false);
	}

		// Access ids
		$page->access_id = (int)get_input('access_id', ACCESS_PRIVATE);
		$page->slug = get_input('slug', '');
		// Write access id
		$page->write_access_id = (int)get_input('write_access_id', ACCESS_PRIVATE);
		
		// Set parent
		$page->parent_guid = $parent_guid;
		
		// Ensure ultimate owner
		$page->owner_guid = ($page->owner_guid ? $page->owner_guid : $_SESSION['user']->guid); 
		
		// finally save
		if ($page->save())
		{
			
			// Now save description as an annotation
			$page->annotate('page', $page->description, $page->access_id);
				
		
			system_message(elgg_echo("pages:saved"));
			
			//add to river
			add_to_river('river/object/page/create','create',$_SESSION['user']->guid,$page->guid);
		
			// Forward to the user's profile
			forward($page->getUrl());
			exit;
		}
		else
			register_error(elgg_echo('pages:notsaved'));

	}
	else
	{
		register_error(elgg_echo("pages:noaccess"));
	}
	

	// Forward to the user's profile
	forward($page->getUrl());
	exit;
?>
