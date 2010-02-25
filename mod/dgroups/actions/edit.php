<?php
	/**
	 * Elgg dgroups plugin edit action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;

	// Get dgroup fields
	$input = array();
	foreach($CONFIG->dgroup as $shortname => $valuetype) {
		$input[$shortname] = get_input($shortname);
		if ($valuetype == 'tags')
			$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
	
	$user_guid = get_input('user_guid');
	$user = NULL;
	if (!$user_guid) $user = $_SESSION['user'];
	else
		$user = get_entity($user_guid);
		
	$dgroup_guid = get_input('dgroup_guid');
	
	$dgroup = new ElggGroup($dgroup_guid); // load if present, if not create a new dgroup
	$dgroup->subtype = 'dgroup';

	if (($dgroup_guid) && (!$dgroup->canEdit()))
	{
		register_error(elgg_echo("dgroups:cantedit"));
		
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// Assume we can edit or this is a new dgroup
	if (sizeof($input) > 0)
	{
		foreach($input as $shortname => $value) {
			$dgroup->$shortname = $value;
		}
	}
	
	// Validate create
	if (!$dgroup->name)
	{
		register_error(elgg_echo("dgroups:notitle"));
		
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// dgroup membership - should these be treated with same constants as access permissions?
	switch (get_input('membership'))
	{
		case 2: $dgroup->membership = ACCESS_PUBLIC; break;
		default: $dgroup->membership = ACCESS_PRIVATE; 
	}
	
	// Set access - all dgroups are public from elgg's point of view.
	$dgroup->access_id = 2;
	
	// Set dgroup tool options
	//$dgroup->files_enable = get_input('files_enable', 'yes');
	//$dgroup->pages_enable = get_input('pages_enable', 'yes');
	//$dgroup->forum_enable = get_input('forum_enable', 'yes');
	
	// Set dgroup tool options
	if (isset($CONFIG->dgroup_tool_options)) {
		foreach($CONFIG->dgroup_tool_options as $dgroup_option) {
			$dgroup_option_toggle_name = $dgroup_option->name."_enable";
			if ($dgroup_option->default_on) {
				$dgroup_option_default_value = 'yes';
			} else {
				$dgroup_option_default_value = 'no';
			}
			$dgroup->$dgroup_option_toggle_name = get_input($dgroup_option_toggle_name, $dgroup_option_default_value);
		}
	}	

	$dgroup->save();
	
	if (!$dgroup->isMember($user))
		$dgroup->join($user); // Creator always a member
	
	
	// Now see if we have a file icon
	if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'],'image/')))
	{
		$prefix = "dgroups/".$dgroup->guid;
		
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $dgroup->owner_guid;
		$filehandler->setFilename($prefix . ".jpg");
		$filehandler->open("write");
		$filehandler->write(get_uploaded_file('icon'));
		$filehandler->close();
		$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
		$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
		$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, true);
		$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
		if ($thumbtiny) {
			
			$thumb = new ElggFile();
			$thumb->owner_guid = $dgroup->owner_guid;
			$thumb->setMimeType('image/jpeg');
			
			$thumb->setFilename($prefix."tiny.jpg");
			$thumb->open("write");
			$thumb->write($thumbtiny);
			$thumb->close();
			
			$thumb->setFilename($prefix."small.jpg");
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			
			$thumb->setFilename($prefix."medium.jpg");
			$thumb->open("write");
			$thumb->write($thumbmedium);
			$thumb->close();
			
			$thumb->setFilename($prefix."large.jpg");
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
				
		}
	}
	
	system_message(elgg_echo("dgroups:saved"));
	
	// Forward to the user's profile
	forward($dgroup->getUrl());
	exit;
?>