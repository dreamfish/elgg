<?php

    /** 
      *  dgroup profile widget - this displays a users dgroups on their profile
      **/
      
    //the number of dgroups to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 4;
		
    //the page owner
	$owner = $vars['entity']->owner_guid;
      
    //$dgroups = get_users_membership($owner);
    //$dgroups = list_entities_from_relationship('member',$owner,false,'dgroup','',0,$number,false,false,false);
	$dgroups = get_entities_from_relationship('member', $owner, false, "dgroup", "", 0, "", $number, 0, false, 0);
	

    if($dgroups){
		
		echo "<div class=\"dgroupmembershipwidget\">";

		foreach($dgroups as $dgroup){
			$icon = elgg_view(
				"dgroups/icon", array(
									'entity' => $dgroup,
									'size' => 'small',
								  )
				);
				
			echo "<div class=\"contentWrapper\">" . $icon . " <div class='search_listing_info'><p><span>" . $dgroup->name . "</span><br />";
			echo $dgroup->briefdescription . "</p></div><div class=\"clearfloat\"></div></div>";
			
		}
		echo "</div>";
    }


   // echo $dgroups;
      
?>