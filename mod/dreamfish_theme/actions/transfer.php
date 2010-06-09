<?php 

	require_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");

	
	$group = get_entity(get_input("group_guid"));
	$new_owner = get_entity(get_input("new_owner_guid"));

	if($group instanceof ElggGroup && $new_owner instanceof ElggUser){
		
		$prefix = "groups/".$group->guid;
		
		if(!empty($group->icontime)){
			
			//copying groupicons
			$originalfile = new ElggFile();
			$originalfile->owner_guid = $group->owner_guid;
			
			$originalfile->setFilename($prefix . ".jpg");
			$originalfile->open("read");
			$master = $originalfile->grabFile();
			$originalfile->close();
			
			$originalfile->setFilename($prefix . "tiny.jpg");
			$originalfile->open("read");
			$thumbtiny = $originalfile->grabFile();
			$originalfile->close();
			
			$originalfile->setFilename($prefix . "small.jpg");
			$originalfile->open("read");
			$thumbsmall = $originalfile->grabFile();
			$originalfile->close();
			
			$originalfile->setFilename($prefix . "medium.jpg");
			$originalfile->open("read");
			$thumbmedium = $originalfile->grabFile();
			$originalfile->close();
			
			$originalfile->setFilename($prefix . "large.jpg");
			$originalfile->open("read");
			$thumblarge = $originalfile->grabFile();
			$originalfile->close();
			
			$save_icons = true;
		}		
		if($group->owner_guid == get_loggedin_userid() || isadminloggedin()){
			if($new_owner->guid != $group->owner_guid){
				// transfering owner
				$group->owner_guid = $new_owner->guid;
				$group->container_guid = $new_owner->guid;
				
				$group->save();
				$group->join($new_owner);
				
				// fixing icons
				if($save_icons){
					$filehandler = new ElggFile();
					$filehandler->owner_guid = $group->owner_guid;
					$filehandler->setFilename($prefix . ".jpg");
					$filehandler->open("write");
					$filehandler->write($master);
					$filehandler->close();
						
					// thumbs
					$thumb = new ElggFile();
					$thumb->owner_guid = $group->owner_guid;
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
				
			} else {
				$error = elgg_echo("group_admin_transfer:transfer:error:owner");
			}
		} else {
			$error = elgg_echo("group_admin_transfer:transfer:error:notowner");
		}
	} else {
		$error = elgg_echo("group_admin_transfer:transfer:error:input");
	}
	
	if($error){
		register_error(elgg_echo("group_admin_transfer:transfer:error") . ": " . $error);
		forward($_SERVER['HTTP_REFERER']);
	} else {
		system_message(sprintf(elgg_echo("group_admin_transfer:transfer:success"), $new_owner->name));
		forward($group->getURL());
	}
	
?>

