<?php

	/**
	 * Elgg invite page
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://elgg.org/
	 */

	$english = array(

		'friends:invite' => 'Invite friends',
		'invitefriends:introduction' => 'To invite friends to join you on this network, enter their email addresses below (one per line):',
		'invitefriends:message' => 'Enter a message they will receive with your invitation:',
		'invitefriends:subject' => 'Invitation to join %s',
	
		'invitefriends:success' => 'Your friends were invited.',
		'invitefriends:failure' => 'Your friends could not be invited.',
	
		'invitefriends:message:default' => '
Hi,

I want to invite you to join my network here on %s.',
		'invitefriends:email' => '
You have been invited to join %s by %s. They included the following message:

%s

To join, click the following link:

	%s

You will automatically add them as a friend when you create your account.',
	
	);
					
	add_translation("en",$english);
?>