<?php
	/**
	 * Elgg relationship create event for dgroups
	 * Display something in the river when a dgroup is joined
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$statement = $vars['statement'];
	
	$performed_by = $statement->getSubject();
	$event = $statement->getEvent();
	$object = $statement->getObject();
	
	if (is_array($object))
	{
		switch ($object['relationship'])
		{
			// Friending
			case 'member' :
				$user = $object['subject'];
				$dgroup = $object['object'];
			
				if (($user instanceof ElggUser) && ($dgroup instanceof ElggGroup))
				{
					echo "<a href=\"{$user->getURL()}\">{$user->name}</a> ";
					echo elgg_echo("dgroups:river:member");
					echo " '<a href=\"{$dgroup->getURL()}\">{$dgroup->name}</a>'";
				}
		
			break;
		}
	}
	
		
?>