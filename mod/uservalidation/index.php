<?php
	/**
	 * User validation plugin.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	// Admins only
	admin_gatekeeper();
	set_page_owner($_SESSION['guid']);
	set_context('admin');
	
	// Make a Query to get all disabled users
	$result = get_data("SELECT guid FROM {$CONFIG->dbprefix}entities WHERE type = 'user' AND enabled = 'no'");
	if (count($result)) 
	{
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		foreach ($result AS $result_guid) 
		{
			$user = get_entity(intval($result_guid->guid));
			if (($user) && ($user instanceof ElggUser))
			{
				$body .= elgg_view('uservalidation/pendingusers', array('entity' => $user));
			}
		}
		access_show_hidden_entities($access_status);
	}
	$title = elgg_view_title(elgg_echo('uservalidation:pendingusers'));
	// Draw the page
	page_draw(elgg_echo('uservalidation:pendingusers'), elgg_view_layout('two_column_left_sidebar', '', $title . $body));
	
?>
