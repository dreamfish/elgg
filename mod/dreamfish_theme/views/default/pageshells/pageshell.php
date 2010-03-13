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

<div id="navigation" class="clearpush">
<ul class="tabnav" id="topleftnav">
<?php if (isloggedin()) { ?>
<li <?php echo strpos(current_page_url(),"") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>"><span><h4>Home</h4></span></a>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/members"><span><h4>People</h4></span></a>
</li>
	
<li <?php echo strpos(current_page_url(),"groups") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/groups/world"><span><h4>Projects</h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"community") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/page/community"><span><h4>Community</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>mod/riverdashboard/">activity</a></li>
	<li><a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">labs</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/dgroups/world">groups</a></li>
	</ul>
</li>
<li <?php echo strpos(current_page_url(),"dashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/dashboard"><span><h4>My Dreamfish</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/dashboard/">dashboard</a></li>
	<li><a href="<?php echo $_SESSION['user']->getURL(); ?>">profile</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/settings/">settings</a></li>
	</ul>
</li>
<!--
<li <?php echo strpos(current_page_url(),"riverdashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/riverdashboard"><span><h4>Activity</h4></span></a>
</li>
-->
<li <?php echo strpos(current_page_url(),"chat") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/chat"><span><h4><span class="chat"></span></h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"messages") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/messages"><span><h4><span class="mail"></span></h4></span></a>
</li>
<? } else { ?>
<li <?php echo current_page_url() == $vars['url'] ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>"><span><h4>Home</h4></span></a>
</li>

<li <?php echo strpos(current_page_url(),"mod/members") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>mod/members"><span><h4>People</h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"groups") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/groups/world"><span><h4>Projects</h4></span></a>
</li>
<li <?php echo strpos(current_page_url(),"community") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/page/community"><span><h4>Community</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">labs</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/dgroups/world">groups</a></li>
	</ul>
</li>
<? }  // end if(isloggedin) ?>

</ul>
<ul class="tabnav"  id="toprightnav">

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