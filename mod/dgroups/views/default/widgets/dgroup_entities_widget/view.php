<?php
	/**
	 * View the widget
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$dgroup_guid = get_input('dgroup_guid');
	$limit = get_input('limit', 8);
	$offset = 0;
	
	if ($vars['entity']->limit)
		$limit = $vars['entity']->limit;
		
	$dgroup_guid = $vars['entity']->dgroup_guid;

	if ($dgroup_guid)
	{	
		$dgroup = get_entity($dgroup_guid);	
		$members = $dgroup->getMembers($limit, $offset);
		$count = $dgroup->getMembers($limit, $offset, true);
		
		$result = list_entities_dgroups("", 0, $dgroup_guid, $limit);
	}
	else
	{
		$result = elgg_echo('dgroups:widgets:entities:label:pleaseedit');
	}
	
	echo $result;
?>