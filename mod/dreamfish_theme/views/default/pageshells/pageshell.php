<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
 */

/**
 * Creates the HTML portion for the community menu
 */
function create_community_navigation_html($vars)
{
	echo ("
	<ul>
	<li><a href=\"". $vars['url'] . "mod/riverdashboard/\">" . elgg_echo('dreamfish_theme:activity') . "</a></li>
	<li><a href=\"". $vars['url'] . "pg/pages/url/dreamfish-emails\">" . elgg_echo('dreamfish_theme:emails') . "</a></li>
	<li><a href=\"". $vars['url'] . "pg/pages/url/dreamfish-events\">" . elgg_echo('dreamfish_theme:events') . "</a></li>
	<li><a href=\"". $vars['url'] . "mod/blogextended/group.php\">" . elgg_echo('dreamfish_theme:blog') . "</a></li>
	<li><a href=\"". $vars['url'] . "pg/dgroups/world\">" . elgg_echo('dreamfish_theme:groups') . "</a></li>
	</ul> ");
}

// Set the content type
header("Content-type: text/html; charset=UTF-8");
// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} else if (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}

?>

<?php echo elgg_view('page_elements/header', $vars); ?>
<?php echo elgg_view('page_elements/header_contents', $vars); ?>
<link REL="SHORTCUT ICON" HREF="<?php echo $vars['url'];?>favicon.ico">
<div id="navigation" class="clearpush">
<ul class="tabnav" id="topleftnav">

<li <?php echo (current_page_url() == $vars['url']) ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/riverdashboard/"><span><h4>Work</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>mod/tasks/worker-request.php">Work requests</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/tasks/user/add">Request a worker</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/groups/world">Projects</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/groups/new/">&nbsp;&nbsp;&nbsp;Create a project</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/members/">People</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18749/">Tips</a></li>
	</ul>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/view/18750/"><span><h4>Impact</h4></span></a>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/view/7468/"><span><h4>About</h4></span></a>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/view/18685/"><span><h4>Community</h4></span></a>
</li>
<li><a href="<?php echo $vars['url']; ?>pg/chat"><span><h4><span class="chat"></span></h4></span></a></li>

<li><a href=""><span><h4 style="color:#AAACAF">+</h4></span></a>
	<ul>
	<li><a href="http://pads.dreamfish.com/ep/pad/newpad">Pad</a></li>	
	</ul>
</li>

<?php if (isloggedin()) { ?>
<li <?php echo strpos(current_page_url(),"dashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/profile"><span><h4>My Dreamfish</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/profile/">Profile</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/profile/edit.php">Edit profile</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/friends">Contacts</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/tasks">Tasks</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/groups/member">Projects</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/dgroups/member">Groups</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/settings/">Settings</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $_SESSION['user']->name ?>/items">Bookmarks</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/messages/">Messages</a></li>
	</ul>
</li>
<? } else { ?>
<li <?php echo strpos(current_page_url(),"dashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/page/login"><span><h4>My Dreamfish</h4></span></a>
</li>
<? } ?>
</ul>

<ul class="tabnav"  id="toprightnav">

<?php if (!isloggedin()) { ?>

<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18693/"><span><h4>Become a member</h4></span></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/page/login"><span><h4>Log in</h4></span></a></li>

<? } else { ?>
<li><a href="<?php echo $vars['url']; ?>action/logout"><span><h4>Log out</h4></span></a></li>
<?php } ?>
</ul>
<div class="clear"></div>
</div>
<?php if (isloggedin()) { ?>
<!--
<div id="navigation" class="internal"> 
		<ul id="menus"> 
<LI> 
  <a href="<?php echo $vars['url']; ?>pg/dashboard/" class="pagelinks"><?php echo elgg_echo('dashboard'); ?></a>

</LI>

    <?php

echo elgg_view("navigation/topbar_tools");

?>


<? echo elgg_view('elgg_topbar/extend', $vars); ?>

 <?       if ($vars['user']->admin || $vars['user']->siteadmin) { ?>
 
 <a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a>
 <?php }     ?>

                </li>
                 <li> 
                  <?php echo elgg_view('output/url', array('href' => "{$vars['url']}action/logout", 'text' => elgg_echo('logout'), 'is_action' => TRUE)); ?>
                  </li>

</ul> 
</div> 
!-->
<?php } ?>
<!-- main contents -->

<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>


<!-- canvas -->
<div id="layout_canvas">


<?php echo $vars['body']; ?>


<div class="clearfloat"></div>
<div id="footer">

</div>
</div><!-- /#layout_canvas -->

<ul class="footerlinkies">
<li class="footercols">
<div class="dflogo">&nbsp;</div>
<ul>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/18784/">Help
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">News
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/18661/">Press</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/18785/">Terms</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/6116/">Privacy</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/6121/">Copyright</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/view/18784/">Contact us</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">Membership</span>
<ul>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18665/">Membership Benefits</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18787/">Hiring Managers</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18694/">Job Seekers</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18669/">Independent Freelancers</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18668/">Entrepreneurs</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18667/">Professionals</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">About</span>
<ul>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18654/">What is Dreamfish</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/3265/">How it works</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/6114/">Humanifesto</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18684/">Peer Learning</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18683/">Cooperative Economy</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18682/">Open Source</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">Get Involved</span>
<ul>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18750/">Impact</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/groups/27/dreamfish-service-team/">Dreamfish Service</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18664/">Dreamfish Fellows</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18662/">Dreamfish Jobs</a></li>
<li><a href="<?php echo $vars['url']; ?>pg/pages/view/18666/">Impact Investing</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">On the Web</span>
<ul>
<li><a href="http://twitter.com/love2dreamfish">Twitter</a></li>
<li><a href="http://www.facebook.com/109012819163948#!/home.php?sk=group_109012819163948&ap=1">Facebook</a></li>
<li><a href="http://www.linkedin.com/groups?mostPopular=&gid=154283">LinkedIn</a></li>
<li><a href="http://www.flickr.com/groups/1004059@N22/pool/">Flickr</a></li>
<li><a href="http://vimeo.com/dreamfish">Vimeo</a></li>
<li><a href="http://www.youtube.com/user/love2dreamfish">Youtube</a></li>
<li><a href="http://plancast.com/dreamfish">Plancast</a></li>
</ul>
</li>

<p>
This work is licensed under a Creative Commons Attribution-Share Alike 3.0 unported <a href="<?php echo $vars['url']; ?>pg/pages/url/copyright-policy">license</a></p>
</li>
</ul>

<hr class="clearrule" />

<div id="poweredby">
Dreamfish is co-owned and built by <a href="<?php echo $vars['url']; ?>pg/pages/url/leadership-and-governance">Dreamfish members</a>. Powered by <a href="http://elgg.org/">Elgg</a> | <a href="http://www.gnu.org/software/mailman/index.html">Mailman</a> | <a href="http://www.vanillaforums.org/">Vanilla Forums</a> | <a href="http://code.google.com/p/etherpad/">Etherpad</a>
</div>

<?       if ($vars['user']->admin || $vars['user']->siteadmin) { ?>
 <a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a> | 
 <a href="<?php echo $vars['url']; ?>mod/pages/" class="usersettings">Maintain Pages</a>
 <?php }     ?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4959867-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
