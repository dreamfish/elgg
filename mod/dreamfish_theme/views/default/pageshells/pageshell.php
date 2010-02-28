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

<style>
body { padding: 0; margin: 0; background-color: rgb(157,16,9); }
#wrapper_header { padding-left: 10px; }
#wrapper_header, #layout_header { background-color: rgb(157,16,9); }
#layout_canvas  { margin: 0;  }
#two_column_left_sidebar {  background-color: rgb(216,244,255); }
#layout-canvas { width: 960px; }
#one_column { background-color: #ffffff; }

/* begin css tabs */

ul.tabnav {
	margin: 0;
	margin-left: 10px;
	padding: 0; /* THIRD number must change with respect to padding-top (X) below */
	list-style-type: none;
}

ul.tabnav li {
	list-style-type: none;
	margin-right: 5px;
	float: left;
	position: relative;
	z-index: 50000;
}

ul.tabnav li a {
	display: block;
	height: 30px;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/tab_left_R.png') left center no-repeat;
	padding-left: 5px;
	text-decoration: none;
	color: #fff;
	font-family: AvantGarde, Verdana;
}

ul.tabnav li a:hover {
	color: #c00;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/tab_left.png') left center no-repeat;
}

ul.tabnav li a span {
	display: block;
	padding-right: 5px;
	height: 30px;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/tab_right_R.png') right center no-repeat;
}

ul.tabnav li a:hover span {
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/tab_right.png') right center no-repeat;
}

ul.tabnav li a span h4 {
	font-weight: normal;
	text-transform: lowercase;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/bg.png');
	line-height: 30px;
}

ul.tabnav li a span h4 span.chat {
	display: block;
	width: 16px;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/chat_alt_stroke_16x16.png') center center no-repeat;
}

ul.tabnav li a span h4 span.mail {
	display: block;
	width: 16px;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/mail_16x12.png') center center no-repeat;
}

ul li a:hover span h4, ul li a:hover span img {
	background: #fff;
}

ul.tabnav li ul { /* second-level lists */
	position: absolute;
	left: 0;
	display: none;
}

ul.tabnav li:hover ul {
	margin: 0;
	padding: 0;
	display: block;
}

ul.tabnav li:hover ul li {
	margin: 0;
	padding: 0;
	clear: both;
}

ul.tabnav li ul li a {
	margin: 0;
	padding: 0 5px;
	color: #ffffff;
	background: url('<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/bg.png');
	text-decoration: none;
	font-family: AvantGarde, Verdana;
	width: 150px;
	display: block;
	line-height: 30px;
}

ul.tabnav li ul li a:hover {
	background-color: #fff;
	color: #ae1a10;
}

#navigation { width: 960px; }
#topleftnav { float: left; width:650px;}
#toprightnav { float: right; width: 250px; }
.clear {
clear:both;
height:0px;
overflow:hidden;
}
#wrapper_header { float: left; width: 600px; }
#container_search { float: left; width: 280px; margin-top:20px; }
#two_column_left_sidebar_maincontent { background-color: #f0f0f0; }
</style>
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
	<li><a href="<?php echo $vars['url']; ?>mod/blogextended/group.php">labs</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/dgroups/world">groups</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/view/209/">updates</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/view/208/">meeting space</a></li>
	</ul>
</li>
<li <?php echo strpos(current_page_url(),"dashboard") ? "class=\"selected\"" :"" ?>>
	<a href="<?php echo $vars['url']; ?>pg/dashboard"><span><h4>My Dreamfish</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $vars['url']; ?>pg/dashboard/">dashboard</a></li>
	<li><a href="<?php echo $_SESSION['user']->getURL(); ?>">profile</a></li>
	<li><a href="#">settings</a></li>
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
	<li><a href="#">groups</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/view/209/">updates</a></li>
	<li><a href="<?php echo $vars['url']; ?>pg/pages/view/208/">meeting space</a></li>
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
<img src="<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/footer.jpg" border="0">
</div>
</div><!-- /#layout_canvas -->
<?       if ($vars['user']->admin || $vars['user']->siteadmin) { ?>
 <a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a> | 
 <a href="<?php echo $vars['url']; ?>mod/pages/" class="usersettings">Maintain Pages</a>
 <?php }     ?>
