/**
 * Elgg Widgets Links module.
 * Adds methods for adding links to the Elgg interface widgets titles.
 *
 * @package widgetlinks
 * @author Adolfo Mazorra
 * @copyright Adolfo Mazorra 2009
 * @version 0.3
 */
 
DESCRIPTION
 
One complain I hear quite often about Elgg profile widgets is that most users
expect that clicking in the widget title should lead them to a page related to
that widget (for example, accessing the wire posts list clicking in the
"thewire" widget).

With this module you can add links to the widget titles. It already adds links
to the most common widgets and it is easy to add links to other widgets. Have a
look to the readme.txt for instructions about this.

If you find any problem using the module or you would like more widgets or url
parameters to be included by default just write me a comment, or if you have
added support for new widgets in your own installation please share it with the
rest of the community.

INTEGRATION WITH OTHER MODULES

The current version adds links to the following modules / widgets:
	* thewire -> thewire
	* friends -> friends
	* tidypics -> album_view, latest, latest_photos
	* messageboard -> messageboard
	* groups -> a_users_groups
	* event_calendar -> event_calendar
	* file -> filerepo
	* pages -> pages
	* bookmarks -> bookmarks
	* izap_video -> izap_video
	* blog -> blog
	* riverdashboard -> river_widget

INSTALLATION

To install just put it in the mod directory and enable it, to update just copy
it over the old version.

HOW TO ADD LINKS TO WIDGET TITLES

To add links to other widgets just edit the file start.php of the module and add
a new line to the widgetlinks_init function like this one:
     
  add_widget_title_link("the widget handler name", "the url for the link");

For example:

	add_widget_title_link("thewire", "[BASEURL]pg/thewire/[USERNAME]");

LINKS URL PARAMETERS

The links can be customized with parameters that are replaced at display time.
The current accepted parameters are:

	* [BASEURL] The base url of the elgg installation
	* [USERNAME] The name of the user owning the current page (not the current
		user unless he's browsing a page he owns).

CHANGELIST:
v0.3
  - Added support for blog, river_widget and izap_video widgets
	- Fixed widget name for tidypics latest_photos (it changed name bewteen versions).
	- Modified the init handler to use priority 9999 (this fixes problems with some
	  plugins that use custom priority too, and also makes this plugin work even if
		it is not the last one in the plugins list).
 
v0.2
	Added support for pages and bookmarks widgets.
	Added css to make the titles with links look as the regular ones.

v0.1
	Initial Release
