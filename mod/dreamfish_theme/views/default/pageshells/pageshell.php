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
<?php if (isloggedin()) { ?>
<li <?php echo (current_page_url() == $vars['url']) ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/url/meeting/"><span><h4>meeting</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/events/">events</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/irc_chat/">chat cafe</a></li>
	<li><a href="http://pads.dreamfish.com/ep/pad/newpad">meeting pad</a></li>
	</ul>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/members"><span><h4>marketplace</h4></span></a>
</li>
	
<li <?php echo strpos(current_page_url(),"groups") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/groups/world"><span><h4>workspace Hub</h4></span></a>
	
	<ul>
	<li>
	  <a href="<?php echo $vars['url'];?>pg/groups/member/<?php echo $_SESSION['user']->username; ?>">my workspaces</a>
	</li>
	</ul>
</li>
<li <?php echo strpos(current_page_url(),"community") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/url/df-community"><span><h4>community</h4></span></a>
	
	<?php create_community_navigation_html($vars); ?>
	
</li>
<li <?php echo strpos(current_page_url(),"dashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/dashboard"><span><h4>my dreamfish</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/dashboard/">dashboard</a></li>
	<li><a href="<?php echo $_SESSION['user']->getURL(); ?>">profile</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/settings/">settings</a></li>
	</ul>
</li>
<!--
<li <?php echo strpos(current_page_url(),"riverdashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/riverdashboard"><span><h4>activity</h4></span></a>
</li>
-->
<li <?php echo strpos(current_page_url(),"chat") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/irc_chat"><span><h4><span class="chat"></span></h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"messages") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/messages"><span><h4><span class="mail"></span></h4></span></a>
</li>
<? } else { ?>
<li <?php echo (current_page_url() == $vars['url']) ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/url/meeting/"><span><h4>meeting</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/events/">events</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/irc_chat/">chat cafe</a></li>
	<li><a href="http://pads.dreamfish.com/ep/pad/newpad">meeting pad</a></li>
	</ul>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/members"><span><h4>marketplace</h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"groups") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/groups/world"><span><h4>workspace Hub</h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"community") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/pages/url/df-community"><span><h4>community</h4></span></a>
	
	<?php create_community_navigation_html($vars) ?>
	<!--
	<ul>
	<li><a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">labs</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/dgroups/world">groups</a></li>
	</ul>
	-->
	
</li>
<? }  // end if(isloggedin) ?>

</ul>
<ul class="tabnav"  id="toprightnav">
<li><a href="<?php echo $vars['url']; ?>pg/pages/url/what-is-dreamfish"><span><h4>about</h4></span></a>

<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/what-is-dreamfish">What is Dreamfish</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/how-dreamfish-works">How Dreamfish Works</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/getting-involved">Our Cooperative</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/url/become_a_member">Become a member</a></li>
</ul>

</li>

<?php if (isloggedin()) { ?>
<li><a href="<?php echo $vars['url']; ?>action/logout"><span><h4><?php echo elgg_echo('logout'); ?></h4></span></a>
 </li>
<? } else { ?>
<li><a href="<?php echo $vars['url']; ?>pg/page/become_a_member"><span><h4>Join Dreamfish</h4></span></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/page/login"><span><h4>Sign In</h4></span></a></li>
<? } ?>
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
<a href="<?php echo $vars['url']; ?>pg/pages/url/help">Help
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">News
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/press">Press</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/terms">Terms</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/privacy">Privacy</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/copyright-policy">Copyright</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/contact">Contact us</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">Membership</span>
<ul>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/what-is-dreamfish">
What is Dreamfish</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/page/become_a_member">Become a member</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/membership-guidelines">Membership guidelines
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/membership-services">Membership services
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/making-a-payment">Making a payment</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/success-stories">Success stories</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">How Dreamfish Works</span>
<ul>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/how-dreamfish-works">Overview
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/learning">Learning</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/selling">Selling</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/hiring">Hiring
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/collaborating">Colllaborating
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/creating-value">Creating value
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/paying-a-worker">Paying a worker</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/getting-started">Getting started</a></li>
</ul>
</li>
<li class="footercols">
<span class="footerh3">Get Involved</span>
<ul>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/dreamfish-fellows">Dreamfish Fellows</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/groups/177/dreamfish-investing-circle">Dreamfish Investing Circle</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/groups/27/dreamfish-network-v10/">Dreamfish Service Team</a></li>
<li class="footercols">
<span class="footerh3">About Dreamfish</span>
<ul>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/humanifesto">Humanifesto</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/labs">Dreamfish Labs
</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/dreamfish-story">Dreamfish Story</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/diversity-statement">Diversity Statement</a></li>
<li>
<a href="<?php echo $vars['url']; ?>pg/pages/url/leadership-and-governance">Leadership and Governance</a></li>
</ul>
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
