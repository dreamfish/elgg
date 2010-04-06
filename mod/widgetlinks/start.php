<?php
	/**
	 * Elgg Widgets Links module.
	 * Adds methods for adding links to the Elgg interface widgets titles.
	 *
	 * @package widgetlinks
	 * @author Adolfo Mazorra
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Adolfo Mazorra 2009
	 */
	
	function add_widget_title_link($handler, $link)
	{
		if (!empty($handler) && !empty($link)) {
			global $CONFIG;
				
				if (isset($CONFIG->widgets) and isset($CONFIG->widgets->handlers) and isset($CONFIG->widgets->handlers[$handler])) {
					$CONFIG->widgets->handlers[$handler]->link = $link;
				}
				
		}
	}
	
	function widgetlinks_init()
	{
		extend_view('css','widgetlinks/css');
		
		add_widget_title_link("thewire", "[BASEURL]pg/thewire/[USERNAME]");
		add_widget_title_link("tasks", "[BASEURL]mod/tasks");
		add_widget_title_link("friends", "[BASEURL]pg/friends/[USERNAME]");
		add_widget_title_link("album_view", "[BASEURL]pg/photos/owned/[USERNAME]");
		add_widget_title_link("latest", "[BASEURL]pg/photos/mostrecent/[USERNAME]");
		add_widget_title_link("latest_photos", "[BASEURL]pg/photos/mostrecent/[USERNAME]");
		add_widget_title_link("messageboard", "[BASEURL]pg/messageboard/[USERNAME]");		
		add_widget_title_link("a_users_groups", "[BASEURL]pg/groups/member/[USERNAME]");		
		add_widget_title_link("event_calendar", "[BASEURL]pg/event_calendar/");
		add_widget_title_link("filerepo", "[BASEURL]pg/file/[USERNAME]");
		add_widget_title_link("pages", "[BASEURL]pg/pages/owned/[USERNAME]");
		add_widget_title_link("bookmarks", "[BASEURL]pg/bookmarks/[USERNAME]/items");
		add_widget_title_link("izap_videos", "[BASEURL]pg/izap_videos/[USERNAME]"); 
		add_widget_title_link("river_widget", "[BASEURL]pg/riverdashboard/");
		add_widget_title_link("blog", "[BASEURL]pg/blog/[USERNAME]");
	}
	
	register_elgg_event_handler('init','system','widgetlinks_init', 9999);
	
?>
