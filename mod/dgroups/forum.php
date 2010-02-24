<?php
	/**
	 * Elgg dgroups forum
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	set_page_owner((int)get_input('dgroup_guid'));
	if (!(page_owner_entity() instanceof ElggGroup)) forward();
	
	group_gatekeeper();
	
	//get any forum topics
	//get any forum topics
	$topics = list_entities_from_annotations("object", "dgroupforumtopic", "dgroup_topic_post", "", 20, 0, get_input('dgroup_guid'), false, false, false);
	set_context('search');	
	$area2 = elgg_view("forum/topics", array('topics' => $topics));
	set_context('dgroups');
	
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
	$title = elgg_echo('item:object:dgroupforumtopic');
	
	// Finally draw the page
	page_draw($title, $body);



?>