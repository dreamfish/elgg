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
error_log("df_header.php loaded!");
?>

<div id="page_container">
<div id="page_wrapper">

<div id="layout_header">
<div id="wrapper_header">
	<!-- display the page title -->
	<!--
	<a href="<?php echo $vars['url']; ?>"><img src="<?php echo $vars['url']; ?>mod/dreamfish_theme/graphics/header.png" border="0"></a></h1>
	-->	
	<a href="/"><img src="<?php echo $wwwroot ?>mod/dreamfish_theme/graphics/header.png" border="0"></a></h1>
</div><!-- /#wrapper_header -->
<div id="container_search">
<?php

function get_request_param($variable) 
{
	if (isset($_REQUEST[$variable])) {			
		if (is_array($_REQUEST[$variable])) {
			$var = $_REQUEST[$variable];
		} else {
			$var = trim($_REQUEST[$variable]);
		}	
	}
}

$value = "";

if (array_key_exists('value', $vars)) {
  $value = $vars['value'];
} elseif ($value = get_request_param('q', get_request_param('tag', NULL))) {
  $value = $value;
} else {
  //$value = elgg_echo('search');
  $value = 'search';
}

$value = stripslashes($value);

?>

<form id="searchform" action="/search/" method="get">
  <input type="hidden" name="searchType" value="fulltext">
  <input type="text" size="21" name="tag" value="<?php echo $value; ?>" onclick="if (this.value=='<?php //echo elgg_echo('search');
  																									    echo 'search'; ?>') { this.value='' }" class="search_input" />
  <input type="submit" value="<?php echo "Search" ?>" class="search_submit_button" />
</form>
</div>
</div><!-- /#layout_header -->