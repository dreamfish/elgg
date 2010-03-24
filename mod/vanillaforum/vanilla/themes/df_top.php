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
//header("Content-type: text/html; charset=UTF-8");
// Set title
//if (empty($vars['title'])) {
//	$title = $vars['config']->sitename;
//} else if (empty($vars['config']->sitename)) {
//	$title = $vars['title'];
//} else {
//	$title = $vars['config']->sitename . ": " . $vars['title'];
//}

?>

<?php //echo elgg_view('page_elements/header', $vars);
	global $Configuration;	
	global $Context;
	
	$pos = strpos($Configuration['WEB_ROOT'],"mod");
	error_log("pos: " . $pos);
	$wwwroot = substr($Configuration['WEB_ROOT'],0,$pos);
	error_log("wwwroot: " . $wwwroot);			
	
	
    $thisdir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    error_log("thisdir: " . $thisdir);
	//include ($thisdir . "/views/default/page_elements/header.php");
	include (dirname(__FILE__) . "/df_header.php");
	
	$UserID = $Context->Session->GetVariable(
		    $Context->Configuration['SESSION_USER_IDENTIFIER'], 'int');
	
?>
<?php //echo elgg_view('page_elements/header_contents', $vars); ?>

<div id="navigation" class="clearpush">
<ul class="tabnav" id="topleftnav">

<?php //if (isloggedin()) { 

if (isset($UserID)) {
	
?>
<li>
	<a href="<?php echo $wwwroot ?>"><span><h4>Home</h4></span></a>
</li>

<li>
	<a href="<?php echo $wwwroot ?>mod/members"><span><h4>People</h4></span></a>
</li>
	
<li <?php echo $wwwroot . "groups" ?>>
	<a href="<?php echo $wwwroot ?>pg/groups/world"><span><h4>Projects</h4></span></a>
</li>
<li <?php echo $wwwroot . "community"?>>
	<a href="<?php echo $wwwroot ?>pg/page/community"><span><h4>Community</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $wwwroot ?>mod/riverdashboard/">activity</a></li>
	<li><a href="<?php echo $wwwroot ?>pg/pages/url/dreamfish-emails">emails</a></li>
	<li><a href="<?php echo $wwwroot ?>pg/pages/url/dreamfish-events">events</a></li>
	<li><a href="<?php echo $wwwroot ?>mod/blogextended/group.php">blog</a></li>
	<li><a href="<?php echo $wwwroot ?>pg/dgroups/world">groups</a></li>
	</ul>
</li>
<li>
	<a href="<?php echo $wwwroot ?>pg/dashboard"><span><h4>My Dreamfish</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $wwwroot ?>pg/dashboard/">dashboard</a></li>
	<li><a href="<?php echo $wwwroot . "pg/profile/" . $Context->Session->User->Name; //$_SESSION['user'];?>">profile</a></li>
	<li><a href="<?php echo $wwwroot ?>pg/settings/">settings</a></li>
	</ul>
</li>

<li>
	<a href="<?php echo $wwwroot ?>pg/chat"><span><h4><span class="chat"></span></h4></span></a>
</li>
<li>
	<a href="<?php echo $wwwroot ?>mod/messages"><span><h4><span class="mail"></span></h4></span></a>
</li>
<? } else { ?>
<li>
	<a href="<?php echo $wwwroot ?>"><span><h4>Home</h4></span></a>
</li>

<li>
	<a href="<?php echo $wwwroot ?>mod/members"><span><h4>People</h4></span></a>
</li>
<li>
	<a href="<?php echo $wwwroot ?>pg/groups/world"><span><h4>Projects</h4></span></a>
</li>
<li>
	<a href="<?php echo $wwwroot ?>pg/page/community"><span><h4>Community</h4></span></a>
	
	<ul>
	<li><a href="<?php echo $wwwroot ?>mod/blogextended/group.php">labs</a></li>
	<li><a href="<?php echo $wwwroot ?>pg/dgroups/world">groups</a></li>
	</ul>
</li>
<? }  // end if(isloggedin) ?>

</ul>
<ul class="tabnav"  id="toprightnav">

<?php //if (isloggedin()) {
		    
if (isset($UserID)) {

?>
<li><a href="<?php echo $wwwroot ?>action/logout"><span><h4><?php echo 'logout';//echo elgg_echo('logout'); ?></h4></span></a>
 </li>
<? } else { ?>
<li><a href="<?php echo $wwwroot ?>pg/page/become_a_member"><span><h4>Join Dreamfish</h4></span></a></li>
<li><a href="<?php echo $wwwroot ?>pg/page/login"><span><h4>Sign In</h4></span></a></li>
<? } ?>
</ul>
<div class="clear"></div>
</div>
<?php //if (isloggedin()) { 

if (isset($UserID)) {

?>
<!--
<div id="navigation" class="internal"> 
		<ul id="menus"> 
<LI> 
  <a href="<?php echo $wwwroot ?>pg/dashboard/" class="pagelinks"><?php //echo elgg_echo('dashboard'); ?></a>

</LI>

    <?php

//echo elgg_view("navigation/topbar_tools");

?>


<? //echo elgg_view('elgg_topbar/extend', $vars); ?>

 <?       if ($vars['user']->admin || $vars['user']->siteadmin) { ?>
 
 <a href="<?php echo $wwwroot ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a>
 <?php }     ?>

                </li>
                 <li> 
                  <?php //echo elgg_view('output/url', array('href' => "{$vars['url']}action/logout", 'text' => elgg_echo('logout'), 'is_action' => TRUE)); ?>
                  </li>

</ul> 
</div> 
-->
<?php } ?>
<!-- main contents -->

<!-- display any system messages -->
<?php //echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>


<!-- canvas -->
<div id="layout_canvas">


<?php //echo $vars['body']; ?>

<!--
<div class="clearfloat"></div>
<div id="footer">

</div>
</div> 
-->
<!-- layout_canvas -->
<!--
<?       if ($vars['user']->admin || $vars['user']->siteadmin) { ?>
 <a href="<?php echo $wwwroot ?>pg/admin/" class="usersettings"><?php //echo elgg_echo("admin"); ?></a> | 
 <a href="<?php echo $wwwroot ?>mod/pages/" class="usersettings">Maintain Pages</a>
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
-->
<!--
</body>
</html>
-->