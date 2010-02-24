<?php
/**
 * Elgg header contents
 * This file holds the header output that a user will see
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 **/

?>

<div id="page_container">
<div id="page_wrapper">

<div id="layout_header">
<div id="wrapper_header">
	<!-- display the page title -->
	<a href="<?php echo $vars['url']; ?>"><img src="<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/header.jpg" border="0"></a></h1>	
</div><!-- /#wrapper_header -->
<div id="container_search">
<?php echo elgg_view('page_elements/searchbox'); ?>
</div>
</div><!-- /#layout_header -->