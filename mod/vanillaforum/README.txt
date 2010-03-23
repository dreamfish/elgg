/**
 * Vanilla forum integration
 * 
 * @package Vanillaforum
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Curverider Ltd 2009
 * @link http://elgg.org/
 * 
 */

REQUIRES

PHP cUrl extension

FEATURES

Adds a Vanilla forum link to the Tools menu. 

Uses Elgg to login and logout of Vanilla. 

Adds an Elgg topbar to Vanilla. 

Uses the Elgg icon for posts and all profile links are redirected
to Elgg.

Adds Vanilla discussion post and comment events to the Elgg activity
river.

Supplies a 'vanillaforum/latest_discussions' view that can be
used by other plugins.

To call the view, use:

echo elgg_view('vanillaforum/latest_discussions',array('limit'=>$limit));

where limit is the number of discussion posts to display. This defaults
to 5 if not supplied.

Optionally supplies a widget that also uses the 
'vanillaforum/latest_discussions' view.

CONFIGURATION SETTINGS

You can configure whether to supply a Vanilla forum widget to your users.

You can also configure whether or not to use a Vanilla logout landing page.

By default, these two options are turned on.

If you turn off the logout landing page, the user will be redirected to:

http://route-to-your-elgg?vanilla_logout=true

If you turn the logout landing page off, Your front page *must* check for the 
vanilla_logout parameter and insert the 'vanillaforum/logout_iframe' view:

if (get_input('vanilla_logout')) {
	echo elgg_view('vanillaforum/logout_iframe');
}

This inserts an invisible iframe that logs the user out of Vanilla forum.

If you turn off the logout landing page and do not put this view in your
front page, your users will not be logged out of Vanilla forum when they logout
of Elgg!

UPGRADING

If you are upgrading an existing version of this plugin, do not copy
over the contents of the "vanilla" directory.

The only file that you need to copy over from that directory
is:

vanilla/library/People/People.Class.Authenticator.php

No other files have been changed and copying over the directory
might over-write configuration information.

Then run upgrade.php or deactivate and reactivate the plugin
to refresh the Elgg cache.

NEW INSTALL

This plugin comes with Vanilla forum, but you still need to 
run the Vanilla install to set it up *before* activating the 
plugin.

1. Set world write permissions for 
mod/vanillaforum/vanilla/conf directory (777)

2. Visit 

http://your-elgg-url/mod/vanillaforum/vanilla

and install Vanilla - leave the table prefix as LUM_

3. Make sure that you set the Vanilla administrative account 
username to be your Elgg admin account.

You should never need to use the Vanilla password you supply 
here to log in (Elgg will handle that for you) but try to make 
it hard to guess and keep a record of it just in case.

4. Once having finished the Vanilla installation, do *not* click on the

"Go sign in and have some fun!"

link on the final install page.

5. You can now restrict the write permissions on the conf directory if you 
like.

6. After the Vanilla install, go to your Elgg tool administration page and 
activate the Vanillaforum plugin.

Then click on the Vanilla forum link in your Tools menu.

UPGRADING VANILLA

In case you want to upgrade the version of Vanilla forum included in this
plugin, I have placed the modified Vanilla files in the "extras" directory
of this plugin.

account.php goes in mod/vanillaforum/vanilla

menu.php and head.php go in mod/vanillaforum/vanilla/themes

and

People.Class.Authenticator.php goes in mod/vanillaforum/vanilla/library/People

Make sure that you remove the vanilla/people.php file to prevent normal
vanilla logins.


